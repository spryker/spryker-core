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
    private function setConfig(string $key, $value): void
    {
        $this->getConfigHelper()->setConfig($key, $value);
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        return $configHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
