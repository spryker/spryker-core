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
     * @return void
     */
    public function storeConfig()
    {
        $pathToConfigLocal = $this->getPathToConfigLocal();
        if (file_exists($pathToConfigLocal)) {
            $this->configCache = file_get_contents($pathToConfigLocal);

            return;
        }

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
        $value = $this->buildValue($value);
        $configuration = sprintf('$config["%s"] = %s;', $key, $value) . PHP_EOL;

        file_put_contents($this->getPathToConfigLocal(), $configuration, FILE_APPEND);
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function buildValue($value)
    {
        if (is_string($value)) {
            $value = sprintf('"%s"', $value);
        }

        if (is_bool($value)) {
            $value = ($value) ? 'true' : 'false';
        }

        if (is_array($value)) {
            $arrayAsString = '[' . PHP_EOL;
            foreach ($value as $key => $innerValue) {
                if (is_string($key)) {
                    $key = sprintf('"%s"', $key);
                }
                $arrayAsString .= sprintf('%s => %s,', $key,  $this->buildValue($innerValue)) . PHP_EOL;
            }
            $arrayAsString .= ']';
            $value = $arrayAsString;
        }

        return $value;
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
        file_put_contents($this->getPathToConfigLocal(), $this->configCache);
    }

}
