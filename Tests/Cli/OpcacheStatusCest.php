<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Tests;

use Pagemachine\OpcacheControl\Tests\CliTester;

final class OpcacheStatusCest
{
    public function _before(CliTester $I)
    {
        $I->runShellCommand('typo3cms install:setup --no-interaction', false);
    }

    public function getOpcacheStatus(CliTester $I)
    {
        $I->runShellCommand('typo3cms opcache:status');

        $I->seeInShellOutput('opcache_enabled');
        $I->seeInShellOutput('opcache_statistics');
    }
}
