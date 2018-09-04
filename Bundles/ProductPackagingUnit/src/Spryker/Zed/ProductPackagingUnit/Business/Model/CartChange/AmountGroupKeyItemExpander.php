<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitGroupKeyGeneratorInterface;

class AmountGroupKeyItemExpander implements AmountGroupKeyItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitGroupKeyGeneratorInterface
     */
    protected $productPackagingUnitGroupKeyGenerator;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitGroupKeyGeneratorInterface $productPackagingUnitGroupKeyGenerator
     */
    public function __construct(
        ProductPackagingUnitGroupKeyGeneratorInterface $productPackagingUnitGroupKeyGenerator
    ) {
        $this->productPackagingUnitGroupKeyGenerator = $productPackagingUnitGroupKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartWithAmountGroupKey(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setGroupKey(
                $this->productPackagingUnitGroupKeyGenerator->getItemWithGroupKey($itemTransfer)
            );
        }

        return $cartChangeTransfer;
    }
}
