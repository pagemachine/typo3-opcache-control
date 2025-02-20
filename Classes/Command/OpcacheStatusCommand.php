<?php

declare(strict_types=1);

namespace Pagemachine\OpcacheControl\Command;

use Pagemachine\OpcacheControl\Action\OpcacheAction;
use Pagemachine\OpcacheControl\Action\OpcacheActionExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OpcacheStatusCommand extends Command
{
    public function __construct(
        private readonly OpcacheActionExecutor $opcacheActionExecutor
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->opcacheActionExecutor->execute(new OpcacheAction(OpcacheAction::STATUS));

        if ($result['success']) {
            $table = new Table($output);

            $this->populateTableFromStatus($table, $result['status']);

            $table->render();

            return Command::SUCCESS;
        }

        $output->writeln(sprintf('Failed: %s', $result['error']));

        return Command::FAILURE;
    }

    private function populateTableFromStatus(Table $table, array $status): void
    {
        foreach ($status as $key => $value) {
            if (is_array($value)) {
                $table->addRow(new TableSeparator());
                $table->addRow([
                    new TableCell($key, [
                        'colspan' => 2,
                        'style' => new TableCellStyle([
                            'cellFormat' => '<info>%s</info>',
                        ]),
                    ]),
                ]);
                $table->addRow(new TableSeparator());

                foreach ($value as $subAspect => $subValue) {
                    $table->addRow([$subAspect, var_export($subValue, true)]);
                }
            } else {
                $table->addRow([$key, var_export($value, true)]);
            }
        }
    }
}
