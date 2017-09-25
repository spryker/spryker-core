<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper\Config;

class ConfigFileStrategy implements ConfigStrategyInterface
{

    /**
     * @var string
     */
    protected $configCache;

    /**
     * @var bool
     */
    protected $fileWasPresent = true;

    /**
     * @return void
     */
    public function storeConfig()
    {
        $pathToConfigLocal = $this->getPathToConfigLocal();
        if (file_exists($pathToConfigLocal)) {
            $this->configCache = file_get_contents($pathToConfigLocal);

            return;
        }

        $this->fileWasPresent = false;
        file_put_contents($pathToConfigLocal, '<?php' . PHP_EOL);
    }

    /**
     * @return string
     */
    protected function getPathToConfigLocal()
    {
        return APPLICATION_ROOT_DIR . '/config/Shared/config_local.php';
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setConfig($key, $value)
    {
        $value = var_export($value, true);
        $configuration = sprintf('$config["%s"] = %s;', $key, $value) . PHP_EOL;

        file_put_contents($this->getPathToConfigLocal(), $configuration, FILE_APPEND);
    }

    /**
     * We can not easily remove an existing configuration so we just set it to null.
     *
     * @param string $key
     *
     * @return void
     */
    public function removeConfig($key)
    {
        file_put_contents($this->getPathToConfigLocal(), '$config[' . $key . '] = null;', FILE_APPEND);
    }

    /**
     * @return void
     */
    public function resetConfig()
    {
        if ($this->fileWasPresent) {
            file_put_contents($this->getPathToConfigLocal(), $this->configCache);

            return;
        }

        unlink($this->getPathToConfigLocal());
    }

}
