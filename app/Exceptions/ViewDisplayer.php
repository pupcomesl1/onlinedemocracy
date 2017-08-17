<?php
/**
 * Created by PhpStorm.
 * User: Marks
 * Date: 2017-08-16
 * Time: 18:42
 */

namespace App\Exceptions;


use Exception;

class ViewDisplayer extends \GrahamCampbell\Exceptions\Displayers\ViewDisplayer
{
    public function render(Exception $exception, array $info, int $code)
    {
        return view('errors.500')->with($info);
    }

    public function canDisplay(Exception $original, Exception $transformed, int $code)
    {
        return true;
    }

}