<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Testify\Helper;

trait FactoryHelperTrait
{
    /**
     * @return \SprykerTest\Client\Testify\Helper\FactoryHelper
     */
    protected function getFactoryHelper(): FactoryHelper
    {
        /** @var \SprykerTest\Client\Testify\Helper\FactoryHelper $factoryHelper */
        $factoryHelper = $this->getModule('\\' . FactoryHelper::class);

        return $factoryHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
