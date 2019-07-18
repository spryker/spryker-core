<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup;

use Spryker\Shared\Setup\SetupConstants;
use Spryker\Zed\Cache\Communication\Console\EmptyAllCachesConsole;
use Spryker\Zed\Installer\Communication\Console\InitializeDatabaseConsole;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Propel\Communication\Console\PropelInstallConsole;
use Spryker\Zed\Search\Communication\Console\SearchConsole;
use Spryker\Zed\Setup\Communication\Console\EmptyGeneratedDirectoryConsole;
use Spryker\Zed\Transfer\Communication\Console\GeneratorConsole;
use Spryker\Zed\ZedNavigation\Communication\Console\BuildNavigationConsole;

class SetupConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Use `getCronjobsDefinitionFilePath()` instead.
     *
     * @return string
     */
    public function getPathForJobsPHP(): string
    {
        return $this->getCronjobsDefinitionFilePath();
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function getJenkinsUrl()
    {
        return $this->get(SetupConstants::JENKINS_BASE_URL);
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function getJenkinsDirectory()
    {
        return $this->get(SetupConstants::JENKINS_DIRECTORY);
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
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
     * @deprecated Will be removed without replacement. Use `vendor/bin/install` instead.
     *
     * Please customize this stack on project level.
     *
     * @return array
     */
    public function getSetupInstallCommandNames()
    {
        return $this->getDefaultSetupInstallCommandNames();
    }

    /**
     * The following commands are a boilerplate stack.
     *
     * For a first initial migration you must use PropelInstallConsole with OPTION_NO_DIFF set to false.
     *
     * @deprecated The commands will be moved to project level in the next major.
     *
     * @return array
     */
    private function getDefaultSetupInstallCommandNames()
    {
        return [
            EmptyAllCachesConsole::COMMAND_NAME,
            EmptyGeneratedDirectoryConsole::COMMAND_NAME,
            PropelInstallConsole::COMMAND_NAME => ['--' . PropelInstallConsole::OPTION_NO_DIFF => true],
            GeneratorConsole::COMMAND_NAME,
            InitializeDatabaseConsole::COMMAND_NAME,
            BuildNavigationConsole::COMMAND_NAME,
            SearchConsole::COMMAND_NAME,
        ];
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * Returns the path to the environment configuration of cronjob functionality.
     *
     * @return string
     */
    public function getCronjobsConfigFilePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_ROOT_DIR,
            'config',
            'Zed',
            'cronjobs',
            'cron.conf',
        ]);
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * Returns the path to the cronjobs definition, their config and schedule.
     *
     * @return string
     */
    public function getCronjobsDefinitionFilePath(): string
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
     * @deprecated Method will be removed without replacement.
     *
     * @return bool
     */
    public function isJenkinsCsrfProtectionEnabled(): bool
    {
        return $this->get(SetupConstants::JENKINS_CSRF_PROTECTION_ENABLED, false);
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * @return bool
     */
    public function isSchedulerEnabled(): bool
    {
        return APPLICATION_ENV !== 'production';
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * @return bool
     */
    public function isDeployVarsEnabled(): bool
    {
        return APPLICATION_ENV !== 'development';
    }
}
