<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductGroup\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductGroupBuilder;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Zed\ProductGroup\Business\ProductGroupFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductGroupDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $productGroupOverride
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function haveProductGroup(array $productGroupOverride = []): ProductGroupTransfer
    {
        $productGroupFacade = $this->getProductGroupFacade();

        $productGroupTransfer = (new ProductGroupBuilder())
            ->seed($productGroupOverride)
            ->build();

        $productGroupTransfer = $productGroupFacade->createProductGroup($productGroupTransfer);

        $this->debug(sprintf(
            'Inserted Product Group: %d',
            $productGroupTransfer->getIdProductGroup()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productGroupTransfer) {
            $this->cleanupProductGroup($productGroupTransfer);
        });

        return $productGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    private function cleanupProductGroup(ProductGroupTransfer $productGroupTransfer): void
    {
        $this->debug(sprintf('Deleting Product Group: %d', $productGroupTransfer->getIdProductGroup()));

        $this->getProductGroupFacade()->deleteProductGroup($productGroupTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\ProductGroupFacadeInterface
     */
    protected function getProductGroupFacade(): ProductGroupFacadeInterface
    {
        return $this->getLocator()->productGroup()->facade();
    }
}
