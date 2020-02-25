<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['ContextMenu']['ItemProviders'][1582221509] =
        \Dsimon\Pagetree\ContextMenu\HidePageTreeItemProvider::class;
}

$iconRegistry = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$iconRegistry->registerIcon(
    'pagetree-plugin', // Icon-Identifier
    TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:pagetree/Resources/Public/Icons/Extension.svg']
);