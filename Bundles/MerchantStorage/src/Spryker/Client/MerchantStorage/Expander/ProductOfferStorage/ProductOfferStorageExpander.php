<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Expander\ProductOfferStorage;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\MerchantStorage\Storage\MerchantStorageReaderInterface;

class ProductOfferStorageExpander implements ProductOfferStorageExpanderInterface
{
    /**
     * @var \Spryker\Client\MerchantStorage\Storage\MerchantStorageReaderInterface
     */
    protected $merchantStorageReader;

    /**
     * @param \Spryker\Client\MerchantStorage\Storage\MerchantStorageReaderInterface $merchantStorageReader
     */
    public function __construct(MerchantStorageReaderInterface $merchantStorageReader)
    {
        $this->merchantStorageReader = $merchantStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        if (!$productOfferStorageTransfer->getMerchantReference()) {
            return $productOfferStorageTransfer;
        }

        /** @var string $productOfferMerchantReference */
        $productOfferMerchantReference = $productOfferStorageTransfer->getMerchantReference();

        $merchantStorageTransfer = $this->merchantStorageReader->findOne(
            (new MerchantStorageCriteriaTransfer())
                ->setMerchantReferences([$productOfferMerchantReference]),
        );

        if (!$merchantStorageTransfer) {
            return $productOfferStorageTransfer;
        }

        return $productOfferStorageTransfer->setMerchantStorage($merchantStorageTransfer);
    }
}
