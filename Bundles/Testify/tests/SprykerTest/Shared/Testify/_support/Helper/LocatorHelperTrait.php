<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait LocatorHelperTrait
{
    /**
     * @return \Generated\Service\Ide\AutoCompletion|\Generated\Zed\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    private function getLocator()
    {
        return $this->getLocatorHelper()->getLocator();
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Testify\Helper\LocatorHelper
     */
    private function getLocatorHelper()
    {
        return $this->getModule('\\' . LocatorHelper::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
