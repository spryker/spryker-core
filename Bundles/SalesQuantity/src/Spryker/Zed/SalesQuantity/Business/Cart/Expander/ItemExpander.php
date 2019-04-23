<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Cart\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface
     */
    protected $salesQuantityRepository;

    /**
     * @param \Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface $salesQuantityRepository
     */
    public function __construct(
        SalesQuantityRepositoryInterface $salesQuantityRepository
    ) {
        $this->salesQuantityRepository = $salesQuantityRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithIsQuantitySplittable(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $isQuantitySplittable = $this->salesQuantityRepository->isProductQuantitySplittable($itemTransfer->getSku());
            $itemTransfer->setIsQuantitySplittable($isQuantitySplittable);
        }

        return $cartChangeTransfer;
    }
}
