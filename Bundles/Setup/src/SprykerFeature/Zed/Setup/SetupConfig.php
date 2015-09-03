<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerEngine\Zed\Propel\Communication\Console\PropelConsole;
use SprykerEngine\Zed\Transfer\Communication\Console\GeneratorConsole;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Application\Communication\Console\BuildNavigationConsole;
use SprykerFeature\Zed\Cache\Communication\Console\DeleteAllCachesConsole;
use SprykerFeature\Zed\Installer\Communication\Console\InitializeDatabaseConsole;
use SprykerFeature\Zed\Setup\Communication\Console\GenerateIdeAutoCompletionConsole;
use SprykerFeature\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use SprykerFeature\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole;

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
        return $this->get(SystemConfig::JENKINS_BASE_URL);
    }

    /**
     * @return string
     */
    public function getJenkinsDirectory()
    {
        return $this->get(SystemConfig::JENKINS_DIRECTORY);
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
            PropelConsole::COMMAND_NAME,
            GeneratorConsole::COMMAND_NAME,
            InitializeDatabaseConsole::COMMAND_NAME,
            GenerateIdeAutoCompletionConsole::COMMAND_NAME,
            RunnerConsole::COMMAND_NAME => ['--' . RunnerConsole::OPTION_TASK_BUILD_ALL],
            BuildNavigationConsole::COMMAND_NAME,
        ];
    }

}
