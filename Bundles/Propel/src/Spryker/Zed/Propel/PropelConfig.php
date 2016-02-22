<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Spryker\Shared\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

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
     * @throws \Exception
     *
     * @return string
     */
    public function getSchemaDirectory()
    {
        $config = Config::get(PropelConstants::PROPEL);
        $schemaDir = $config['paths']['schemaDir'] . DIRECTORY_SEPARATOR;

        return $schemaDir;
    }

    /**
     * @return array
     */
    public function getPropelSchemaPathPatterns()
    {
        return [
            APPLICATION_SPRYKER_ROOT . '/*/src/*/Zed/*/Persistence/Propel/Schema/',
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
