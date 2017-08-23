<?php

namespace App\Jobs;

use App\Badge;
use App\PointsHistoryEntry;
use App\Proposition;
use App\PropositionFactory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class AssignVotePoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;
    protected $propId;
    protected $value;

    /**
     * Create a new job instance.
     *
     * @param $uid int User ID
     * @param $propId int Proposition ID
     * @param $value int 1 for upvote, 0 for downvote
     */
    public function __construct($uid, $propId, $value)
    {
        $this->uid = $uid;
        $this->propId = $propId;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $factory = new PropositionFactory();
        // Bail out if the user has deleted their vote in the meantime
        if ($factory->getUserVoteStatus($this->propId, $this->uid)) {
            Redis::del('votegrace:' . $this->uid . ':' . $this->propId);

            $prop = Proposition::find($this->propId);
            if ($this->value === 1) {
                PointsHistoryEntry::add($prop->proposer()->id, 15, 'receive_upvote');
                Badge::tryAward($prop->proposer()->id, 'supported', $prop);
                Badge::tryAward($prop->proposer()->id, 'encouraged', $prop);
                Badge::tryAward($prop->proposer()->id, 'empowered', $prop);
                Badge::tryAward($prop->proposer()->id, 'well_received', $prop);
            }
            Cache::forget('leaderboard');
        }
        $this->delete();
    }
}
