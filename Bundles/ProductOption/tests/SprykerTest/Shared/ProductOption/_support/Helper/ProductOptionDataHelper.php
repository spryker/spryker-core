<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductOption\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOptionGroupBuilder;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductOptionDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $productOptionGroupOverride
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function haveProductOptionGroup(array $productOptionGroupOverride = []): ProductOptionGroupTransfer
    {
        $productOptionGroupTransfer = (new ProductOptionGroupBuilder($productOptionGroupOverride))
            ->withProductOptionValue()
            ->withAnotherProductOptionValue()
            ->withGroupNameTranslation()
            ->withProductOptionValueTranslation()
            ->build();

        $idProductOptionGroup = $this->getProductOptionFacade()->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setIdProductOptionGroup($idProductOptionGroup);

        return $productOptionGroupTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface
     */
    protected function getProductOptionFacade(): ProductOptionFacadeInterface
    {
        return $this->getLocator()->productOption()->facade();
    }
}
