<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Codecept;
use Codeception\Command\Run;
use Codeception\Command\Shared\Config as CommandConfig;
use Codeception\Configuration;
use Codeception\CustomCommandInterface;
use Codeception\Exception\TestRuntimeException;
use PHPUnit\Runner\Version;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesCommand extends Run implements CustomCommandInterface
{
    use CommandConfig;

    /**
     * @return string
     */
    public static function getCommandName()
    {
        return 'fixtures';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Builds fixtures and serializes them into files';
    }

    /**
     * Sets Run arguments
     *
     * @return void
     */
    protected function configure()
    {
        $this->setDefinition([
            new InputOption(
                'no-colors',
                '',
                InputOption::VALUE_NONE,
                'Force no colors in output (useful to override config file)'
            ),
            new InputOption('silent', '', InputOption::VALUE_NONE, 'Only outputs suite names and final results'),
            new InputOption('steps', '', InputOption::VALUE_NONE, 'Show steps in output'),
            new InputOption('debug', 'd', InputOption::VALUE_NONE, 'Show debug and scenario output'),
            new InputOption('no-exit', '', InputOption::VALUE_NONE, 'Don\'t finish with exit code'),
            new InputOption(
                'skip',
                's',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Skip selected suites'
            ),
            new InputOption(
                'group',
                'g',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Groups of fixtures to be build'
            ),
            new InputOption(
                'skip-group',
                'x',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Skip selected groups'
            ),
            new InputOption(
                'env',
                '',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Run tests in selected environments.'
            ),
            new InputOption(
                'seed',
                '',
                InputOption::VALUE_REQUIRED,
                'Define random seed for shuffle setting'
            ),
        ]);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Codeception\Exception\TestRuntimeException
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->options = $input->getOptions();
        $this->output = $output;

        // load config
        $config = $this->getGlobalConfig();

        if (!$this->options['silent']) {
            $output->writeln(
                Codecept::versionString() . "\nPowered by " . Version::getVersionString()
            );
            $output->writeln(
                "Running with seed: " . $this->options['seed'] . "\n"
            );
        }
        if ($this->options['group']) {
            $output->writeln(sprintf("[Groups] <info>%s</info> ", implode(', ', $this->options['group'])));
        }
        if ($this->options['debug']) {
            $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        }

        $userOptions = array_intersect_key($this->options, array_flip($this->passedOptionKeys($input)));
        $userOptions['verbosity'] = $output->getVerbosity();
        $userOptions['seed'] = (int)$this->options['seed'] ?: mt_rand();
        $userOptions['colors'] = $this->options['no-colors'] || $input->getOption('no-ansi') ? false : $config['settings']['colors'];
        $userOptions['groups'] = $this->options['group'];
        $userOptions['excludeGroups'] = $this->options['skip-group'];

        $this->codecept = new FixturesRunner($userOptions);

        $suites = Configuration::suites();
        $this->executed = $this->runSuites($suites);

        if (!empty($config['include'])) {
            $current_dir = Configuration::projectDir();
            $suites += $config['include'];
            $this->runIncludedSuites($config['include'], $current_dir);
        }

        if ($this->executed === 0) {
            throw new TestRuntimeException(
                sprintf("Suite '%s' could not be found", implode(', ', $suites))
            );
        }

        $this->codecept->printResult();

        if (!$input->getOption('no-exit') && !$this->codecept->getResult()->wasSuccessful()) {
            exit(1);
        }
    }
}
