<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper\Config;

interface ConfigStrategyInterface
{

    /**
     * Store the current configuration
     *
     * @return void
     */
    public function storeConfig();

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setConfig($key, $value);

    /**
     * Remove or set a configuration value to null
     *
     * @param string $key
     *
     * @return void
     */
    public function removeConfig($key);

    /**
     * Reset the configuration to its initial state
     *
     * @return void
     */
    public function resetConfig();

}
