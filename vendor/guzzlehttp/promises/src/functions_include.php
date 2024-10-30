<?php

namespace Convoworks;

// Don't redefine the functions if included multiple times.
if (!\function_exists('Convoworks\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
