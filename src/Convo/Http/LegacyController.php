<?php

namespace Convo\Http;

use function Convo\view;
class LegacyController extends \Convo\Http\Controller
{
    /**
     * Display the main dashboard page
     */
    public static function index()
    {
        view('legacy/index');
    }
}
