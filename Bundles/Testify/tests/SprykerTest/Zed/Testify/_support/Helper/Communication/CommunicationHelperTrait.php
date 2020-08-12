<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Communication;

trait CommunicationHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelper
     */
    protected function getCommunicationHelper(): CommunicationHelper
    {
        /** @var \SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelper $factoryHelper */
        $factoryHelper = $this->getModule('\\' . CommunicationHelper::class);

        return $factoryHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
