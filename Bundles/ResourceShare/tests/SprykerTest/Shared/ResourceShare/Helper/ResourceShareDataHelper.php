<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ResourceShare\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ResourceShareBuilder;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery;
use Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ResourceShareDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

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

        $this->getDataCleanupHelper()->_addCleanup(function () use ($resourceShareTransfer): void {
            $this->cleanupResourceShare($resourceShareTransfer);
        });

        return $resourceShareTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return void
     */
    protected function cleanupResourceShare(ResourceShareTransfer $resourceShareTransfer): void
    {
        SpyResourceShareQuery::create()
            ->filterByIdResourceShare($resourceShareTransfer->getIdResourceShare())
            ->findOne()
            ->delete();
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface
     */
    protected function getResourceShareFacade(): ResourceShareFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->resourceShare()->facade();
    }
}
