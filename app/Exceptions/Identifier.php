<?php
/**
 * Created by PhpStorm.
 * User: Marks
 * Date: 2017-08-16
 * Time: 19:27
 */

namespace App\Exceptions;


use Exception;
use GrahamCampbell\Exceptions\ExceptionIdentifier;

class Identifier extends ExceptionIdentifier
{
    public function identify(Exception $exception)
    {
        if (!isset($GLOBALS['sentryId'])) {
            return app('sentry')->captureException($exception);
        }
        return $GLOBALS['sentryId'];
    }

}