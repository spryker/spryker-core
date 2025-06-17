<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @param \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct(protected ProductOfferStorageClientInterface $productOfferStorageClient)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithProductOfferReference(
        ItemTransfer $itemTransfer,
        array $params
    ): ItemTransfer {
        if (!isset($params[static::PARAM_PRODUCT_OFFER_REFERENCE])) {
            return $itemTransfer;
        }

        $productOfferReference = $params[static::PARAM_PRODUCT_OFFER_REFERENCE] ?: null;

        if (!$productOfferReference) {
            return $itemTransfer;
        }

        $productOfferStorageTransfer = $this->productOfferStorageClient->findProductOfferStorageByReference($productOfferReference);

        if (!$productOfferStorageTransfer) {
            return $itemTransfer;
        }

        return $itemTransfer->setProductOfferReference($productOfferReference);
    }
}
