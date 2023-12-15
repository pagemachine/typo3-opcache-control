<?php

declare(strict_types=1);

namespace Pagemachine\OpcacheControl\Command;

use Pagemachine\OpcacheControl\Action\OpcacheAction;
use Pagemachine\OpcacheControl\Action\OpcacheActionExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OpcacheResetCommand extends Command
{
    private OpcacheActionExecutor $opcacheActionExecutor;

    public function __construct(
        OpcacheActionExecutor $opcacheActionExecutor
    ) {
        $this->opcacheActionExecutor = $opcacheActionExecutor;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->opcacheActionExecutor->execute(new OpcacheAction(OpcacheAction::RESET));

        if ($result['success']) {
            $output->writeln('<info>Success: opcache reset</info>');

            return Command::SUCCESS;
        }

        $output->writeln(sprintf('<error>Failed: %s</error>', $result['error']));

        return Command::FAILURE;
    }
}
