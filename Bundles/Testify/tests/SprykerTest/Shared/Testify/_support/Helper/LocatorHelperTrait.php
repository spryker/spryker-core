<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait LocatorHelperTrait
{
    /**
     * @return \Generated\Service\Ide\AutoCompletion&\Generated\Zed\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->getLocatorHelper()->getLocator();
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\LocatorHelper
     */
    protected function getLocatorHelper(): LocatorHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\LocatorHelper $locatorHelper */
        $locatorHelper = $this->getModule('\\' . LocatorHelper::class);

        return $locatorHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
