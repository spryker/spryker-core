<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Helper;

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
     * @return \Codeception\Module|\Testify\Helper\Config
     */
    private function getConfigHelper()
    {
        return $this->getModule('\\' . Dependency::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);

}
