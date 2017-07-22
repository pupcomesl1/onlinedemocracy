<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function deploy(Request $request) {
        // First, verify it's actually from GitHub
        if (!$request->hasHeader('X-Hub-Signature')) {
            return response('No signature', 403);
        }
        $post = $request->getContent();
        list($algo, $hash) = explode('=', $request->header('X-Hub-Signature'), 2) + array('', '');
        if (!in_array($algo, hash_algos(), TRUE)) {
            return response('Hash algorithm ' . $algo . ' is not supported.', 500);
        }
        if (!hash_equals($hash, hash_hmac($algo, $post, env('GITHUB_DEPLOY_SECRET', 'capitalism')))) {
            return response('Invalid signature', 403);
        }

        // Verified. Next, put the site into maintenance mode.
        \Artisan::call('down');

        // Check if the update contains database migrations.
        `cd ..`; // by default we're in public/
        `git fetch origin production 2>&1`;

        // Since the next step will wipe out the logs, let's back them up first
        if (!ENV('DEPLOY_DISABLE_LOG_BACKUP') == 'true') {
            $time = `git log production..origin/production -1  --pretty=format:%ct`;
            `cp storage/logs/laravel.log /var/log/directdemocracy/laravel-deploy-{$time}.log 2>&1`;
        }

        // This step MUST be before the git reset
        $diff = `git diff --name-only production origin/production 2>&1`;
        $migrationsNecessary = strpos($diff, 'migrations') !== false;

        // Alright. Time to actually deploy the code!
        `git reset --hard origin/production 2>&1`;

        // Run migrations if necessary
        if ($migrationsNecessary) {
            \Artisan::call('migrate');
        }

        // Okay, we should be running on the new code now.
        // Flip maintenance mode off and we should be good to go!
        \Artisan::call('up');
        return response('ok', 200);
    }
}
