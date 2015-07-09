<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * Class BaseCommand
 */
abstract class BaseCommand extends Console
{

    const COMMAND_NAME = 'git:base';

    const OPTION_PACKAGE = 'pac';
    const OPTION_PACKAGE_SHORT = 'p';

    const OPTION_ONLY_PACKAGE = 'only-packages';
    const OPTION_ONLY_PACKAGE_SHORT = 'o';

    const OPTION_ONLY_PROJECT = 'only-project';
    const OPTION_ONLY_PROJECT_SHORT = 'x';

    /**
     * @var string
     */
    private $packageOption;

    /**
     * @var string
     */
    private $onlyPackagesOption;

    /**
     * @var string
     */
    private $onlyProjectOption;

    /**
     * @var array
     */
    protected $packages = ['all'];

    protected function configure()
    {
        $this->setDescription(
            'Execute \'' . str_replace(':', ' ', static ::COMMAND_NAME) . '\' on all spryker packages'
        );
        $this->setHelp(
            'This Command will run on working directory and all spryker packages.
            If option "--pac=XY" is used, it will only run on working directory and
            all given spryker packages.

            Examples:
             - git-{command} will run on working directory and all spryker packages
             - git-{command} --pac=ab will run on working directory and spryker package "ab-package"
             - git-{command} --pac=ab --pac=cd  will run on working directory and spryker packages "ab-package" and "ab-package"
            ');

        $this->addOption(
            self::OPTION_PACKAGE,
            self::OPTION_PACKAGE_SHORT,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'add packages on which command should run'
        );

        $this->addOption(
            self::OPTION_ONLY_PACKAGE,
            self::OPTION_ONLY_PACKAGE_SHORT,
            InputOption::VALUE_NONE,
            'If set this command will only run on given packages'
        );

        $this->addOption(
            self::OPTION_ONLY_PROJECT,
            self::OPTION_ONLY_PROJECT_SHORT,
            InputOption::VALUE_NONE,
            'If set this command will only run for project'
        );

        $this->specifyPackageOptions(self::OPTION_PACKAGE, self::OPTION_ONLY_PACKAGE, self::OPTION_ONLY_PROJECT);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getPackages();
        $this->sendCommandMessage();
        $this->loopAllDirectories();
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    protected function runCommand($dir)
    {
        $this->info($dir);
        if (!$this->checkGit($dir)) {
            return false;
        }
        $dirParts = explode(DIRECTORY_SEPARATOR, $dir);
        $folder = end($dirParts);

        $command = $this->computeCommand();

        $this->printLineSeparator();
        $this->info($command . ' for ' . $folder);
        $this->printLineSeparator();
        $process = new Process($command, $dir);
        $process->run(
            function ($type, $data) {
                $this->info($data, false);
            }
        );

        return true;
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    protected function checkGit($dir)
    {
        if (!is_dir($dir . DIRECTORY_SEPARATOR . '.git')) {
            return false;
        } else {
            return true;
        }
    }

    abstract protected function sendCommandMessage();

    abstract protected function computeCommand();

    /**
     * @param string $packageOption
     * @param string $onlyPackagesOption
     * @param string $onlyProjectOption
     */
    protected function specifyPackageOptions($packageOption, $onlyPackagesOption, $onlyProjectOption)
    {
        $this->packageOption = $packageOption;
        $this->onlyPackagesOption = $onlyPackagesOption;
        $this->onlyProjectOption = $onlyProjectOption;
    }

    /**
     * @return array
     */
    protected function getPackages()
    {
        if ($this->input->getOption($this->packageOption)) {
            $packages = $this->input->getOption($this->packageOption);
            $callback = function ($packageName) {
                return $packageName;
            };
            $this->packages = array_map($callback, $packages);
        }

        return $this->packages;
    }

    /**
     * @return Finder
     */
    protected function getPackageDirs()
    {
        $finder = new Finder();
        $finder->depth(0)->directories()->in(APPLICATION_VENDOR_DIR . '/spryker')->exclude('silex-routing');
        if (!in_array('all', $this->getPackages())) {
            $finder->filter($this->getCallback());
        }

        return $finder;
    }

    /**
     * @return callable
     */
    protected function getCallback()
    {
        return function (\SplFileInfo $file) {
            return (in_array($file->getBasename(), $this->getPackages()));
        };
    }

    /**
     * @return string
     */
    protected function getCommaSeparatedPackages()
    {
        return implode(', ', $this->getPackages());
    }

    protected function loopAllDirectories()
    {
        if (!$this->input->getOption($this->onlyPackagesOption)) {
            $this->runCommand(APPLICATION_ROOT_DIR);
        }
        if (!$this->input->getOption($this->onlyProjectOption)) {
            foreach ($this->getPackageDirs() as $packageDir) {
                $this->runCommand($packageDir);
            }
        }
    }

}
