<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait FactoryHelperTrait
{
    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Testify\Helper\FactoryHelper
     */
    private function getFactoryHelper()
    {
        return $this->getModule('\\' . FactoryHelper::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
