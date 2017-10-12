<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait ConfigHelperTrait
{
    /**
     * @param string $key
     * @param string|int|array|float|bool $value
     *
     * @return void
     */
    private function setConfig($key, $value)
    {
        $this->getConfigHelper()->setConfig($key, $value);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    private function removeConfig($key)
    {
        $this->getConfigHelper()->removeConfig($key);
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    private function getConfigHelper()
    {
        return $this->getModule('\\' . ConfigHelper::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
