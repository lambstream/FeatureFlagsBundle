<?php

namespace DZunke\FeatureFlagsBundle\Command;

use DZunke\FeatureFlagsBundle\Toggle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'dzunke:feature_flags:debug',
    description: 'Debugging Feature Flags',
)]
class DebugFlagsCommand extends Command
{
    public function __construct(private readonly Toggle $toggle, private readonly Toggle\ConditionBag $conditionBag)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');
        $output->writeln('<fg=cyan>Debugging Feature Flags</fg=cyan>');
        $output->writeln('<fg=cyan>=======================</fg=cyan>');
        $output->writeln('');

        $output->writeln('<fg=cyan>Existing Flag-Conditions</fg=cyan>');
        $output->writeln('<fg=cyan>------------------------</fg=cyan>');
        $output->writeln('');

        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(['name', 'class']);
        foreach ($this->conditionBag as $name => $condition) {
            $table->addRow([$name, $condition::class]);
        }
        $table->render();

        $output->writeln('');

        $output->writeln('<fg=cyan>Configured Feature-Flags</fg=cyan>');
        $output->writeln('<fg=cyan>------------------------</fg=cyan>');
        $output->writeln('');

        $this->renderFlagsTable($output);

        $output->writeln('');

        return Command::SUCCESS;
    }

    private function renderFlagsTable(OutputInterface $output): void
    {
        $flags = $this->toggle->getFlags();
        if (empty($flags)) {
            $output->writeln('<comment> ! [NOTE] there are no flags configured</comment>');
            return;
        }

        ksort($flags);

        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(['name', 'conditions']);

        foreach ($flags as $name => $flag) {
            $table->addRow([$name, implode(', ', array_keys($flag->getConfig()))]);
        }

        $table->render();
    }
}
