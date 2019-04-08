<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ResourceShare\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ResourceShareBuilder;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ResourceShareDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function haveResourceShare(array $seedData = []): ResourceShareTransfer
    {
        $resourceShareTransfer = (new ResourceShareBuilder($seedData))->build();

        $resourceShareResponseTransfer = $this->getResourceShareFacade()->generateResourceShare($resourceShareTransfer);
        $resourceShareTransfer = $resourceShareResponseTransfer->getResourceShare();

        return $resourceShareTransfer;
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface
     */
    protected function getResourceShareFacade(): ResourceShareFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->resourceShare()->facade();
    }
}
