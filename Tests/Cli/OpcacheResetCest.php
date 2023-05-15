<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Tests;

use Pagemachine\OpcacheControl\Tests\CliTester;

final class OpcacheResetCest
{
    private const NUM_CACHED_SCRIPTS_PATTERN = '/num_cached_scripts[^\d]+(?P<num_cached_scripts>\d+)/';

    public function _before(CliTester $I)
    {
        $I->runShellCommand('typo3cms install:setup --no-interaction || typo3 setup --no-interaction', false);
    }

    /**
     * @depends Pagemachine\OpcacheControl\Tests\OpcacheStatusCest:getOpcacheStatus
     */
    public function resetOpcache(CliTester $I)
    {
        $I->runShellCommand('typo3 opcache:status');

        preg_match_all(
            self::NUM_CACHED_SCRIPTS_PATTERN,
            $I->grabShellOutput(),
            $statisticsBefore
        );

        $I->runShellCommand('typo3 opcache:reset');

        $I->seeInShellOutput('Success: opcache reset');

        $I->runShellCommand('typo3 opcache:status');

        preg_match_all(
            self::NUM_CACHED_SCRIPTS_PATTERN,
            $I->grabShellOutput(),
            $statisticsAfter
        );

        $I->assertNotEquals(
            $statisticsBefore['num_cached_scripts'],
            $statisticsAfter['num_cached_scripts']
        );
    }
}
