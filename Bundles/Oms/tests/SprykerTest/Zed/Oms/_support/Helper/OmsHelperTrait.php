<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Helper;

use Codeception\Module;

trait OmsHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Oms\Helper\OmsHelper
     */
    protected function getOmsHelper(): OmsHelper
    {
        if (!$this->hasModule('\\' . OmsHelper::class)) {
            $this->moduleContainer->create('\\' . OmsHelper::class);
        }

        /** @var \SprykerTest\Zed\Oms\Helper\OmsHelper $omsHelper */
        $omsHelper = $this->getModule('\\' . OmsHelper::class);

        return $omsHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
