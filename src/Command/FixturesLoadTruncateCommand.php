<?php

namespace PhpSolution\FunctionalTest\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * FixturesLoadTruncateCommand
 */
class FixturesLoadTruncateCommand extends Command
{
    const NAME = 'functional-test:fixtures:load';
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * FixturesLoadTruncateCommand constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct(self::NAME);
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Load fixtures with truncate all tables')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->managerRegistry->getConnection($input->getOption('connection'));
        $connection->prepare('SET FOREIGN_KEY_CHECKS=0')->execute();

        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $parameters = [
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
            '--purge-with-truncate' => true,
        ];

        $input = new ArrayInput($parameters);
        $input->setInteractive(false);
        $returnCode = $command->run($input, $output);
        $connection->prepare('SET FOREIGN_KEY_CHECKS=1')->execute();

        $text = '';
        if ($returnCode === 0) {
            $text .= 'fixtures successfully loaded ...';
        }
        $output->writeln($text);
    }
}