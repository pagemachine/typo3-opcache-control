<?php
defined('TYPO3') or die('Access denied');

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][\Pagemachine\OpcacheControl\Action\OpcacheAction::RESET] = \Pagemachine\OpcacheControl\Action\OpcacheResetAction::class;
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][\Pagemachine\OpcacheControl\Action\OpcacheAction::STATUS] = \Pagemachine\OpcacheControl\Action\OpcacheStatusAction::class;
