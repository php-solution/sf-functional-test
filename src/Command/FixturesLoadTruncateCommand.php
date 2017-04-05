<?php
namespace PhpSolution\FunctionalTest\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class FixturesLoadTruncateCommand
 *
 * @package PhpSolution\FunctionalTest\Command
 */
class FixturesLoadTruncateCommand extends ContainerAwareCommand
{
    const NAME = 'functional-test:load-fixtures';

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Load fixtures with truncate all tables')
            ->addOption('fixtures', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY)
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
        $connection = $this->getContainer()->get('doctrine')->getConnection($input->getOption('connection'));
        $connection->prepare('SET FOREIGN_KEY_CHECKS=0')->execute();

        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $defaultOptions = [
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
            '--purge-with-truncate' => true,
        ];
        $options = [];
        if (!empty($fixturesPath = $input->getOption('fixtures'))) {
            $kernelRootDir = $this->getContainer()->getParameter('kernel.root_dir');
            $fixturesPath = realpath(str_replace('%kernel.root_dir%', $kernelRootDir, $fixturesPath));
            $options['--fixtures'] = $fixturesPath;
        }
        $arguments = array_merge($options, $defaultOptions);

        $input = new ArrayInput($arguments);
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