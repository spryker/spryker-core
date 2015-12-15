<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Propel\Communication\Console\PropelInstallConsole;
use Spryker\Zed\Transfer\Communication\Console\GeneratorConsole;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Application\Communication\Console\BuildNavigationConsole;
use Spryker\Zed\Cache\Communication\Console\DeleteAllCachesConsole;
use Spryker\Zed\Installer\Communication\Console\InitializeDatabaseConsole;
use Spryker\Zed\Search\Communication\Console\SearchConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use Spryker\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole;

class SetupConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getPathForJobsPHP()
    {
        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_ROOT_DIR,
            'config',
            'Zed',
            'cronjobs',
            'jobs.php',
        ]);
    }

    /**
     * @return string
     */
    public function getJenkinsUrl()
    {
        return $this->get(ApplicationConstants::JENKINS_BASE_URL);
    }

    /**
     * @return string
     */
    public function getJenkinsDirectory()
    {
        return $this->get(ApplicationConstants::JENKINS_DIRECTORY);
    }

    /**
     * @return string
     */
    public function getJenkinsJobsDirectory()
    {
        return $this->getJenkinsDirectory() . '/jobs';
    }

    /**
     * @return string
     */
    public function getGeneratedDirectory()
    {
        return APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . 'Generated';
    }

    /**
     * @return array
     */
    public function getSetupInstallCommandNames()
    {
        return [
            DeleteAllCachesConsole::COMMAND_NAME,
            RemoveGeneratedDirectoryConsole::COMMAND_NAME,
            PropelInstallConsole::COMMAND_NAME => ['--' . PropelInstallConsole::OPTION_NO_DIFF => true],
            GeneratorConsole::COMMAND_NAME,
            InitializeDatabaseConsole::COMMAND_NAME,
            GenerateIdeAutoCompletionConsole::COMMAND_NAME,
            RunnerConsole::COMMAND_NAME => ['--' . RunnerConsole::OPTION_TASK_BUILD_ALL],
            BuildNavigationConsole::COMMAND_NAME,
            SearchConsole::COMMAND_NAME,
        ];
    }

}
