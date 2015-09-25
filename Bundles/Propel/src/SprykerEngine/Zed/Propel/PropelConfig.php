<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;

class PropelConfig extends AbstractBundleConfig
{

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
    public function getPropelSchemaPathPatterns()
    {
        return [
            APPLICATION_VENDOR_DIR . '/*/*/*/*/src/*/Zed/*/Persistence/Propel/Schema/'
        ];
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/logs/ZED/propel.log';
    }

}
