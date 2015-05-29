<?php

namespace SprykerFeature\Zed\Setup;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;

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
            'jobs.php'
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
     * @return string
     * @throws \Exception
     */
    public function getSchemaDirectory()
    {
        $config = Config::get(SystemConfig::PROPEL);
        $schemaDir = $config['paths']['schemaDir'] . DIRECTORY_SEPARATOR;

        return $schemaDir;
    }

    /**
     * @return array
     */
    public function getPropelSchemaPathPattern()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Persistence/Propel/Schema/'
        ];
    }

    /**
     * @return string
     */
    public function getPropelSchemaFileNamePattern()
    {
        return '*.schema.xml';
    }
}
