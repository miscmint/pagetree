<?php

use Dsimon\Pagetree\Controller\PageTreeController;

/**
 * Definitions for routes provided by EXT:pagetree
 */
return [
    'hide_whole_pagetree_from_here' => [
        'path' => '/pagetree/hide',
        'access' => 'public',
        'target' => PageTreeController::class . '::processAjaxRequest'
    ],
];