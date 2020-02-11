<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Testify\Helper;

trait FactoryHelperTrait
{
    /**
     * @return \SprykerTest\Yves\Testify\Helper\FactoryHelper
     */
    protected function getFactoryHelper(): FactoryHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\FactoryHelper $factoryHelper */
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
