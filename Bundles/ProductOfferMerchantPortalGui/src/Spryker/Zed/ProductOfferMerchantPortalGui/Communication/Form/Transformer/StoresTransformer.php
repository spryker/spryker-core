<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class StoresTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]|null $storeTransfers
     *
     * @return int[]|null
     */
    public function transform($storeTransfers)
    {
        if ($storeTransfers === null) {
            return null;
        }

        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }

    /**
     * @param int[]|null $storeIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]|null
     */
    public function reverseTransform($storeIds)
    {
        if ($storeIds === null) {
            return null;
        }

        $storeTransfers = new ArrayObject();

        foreach ($storeIds as $idStore) {
            $storeTransfers->append(
                (new StoreTransfer())->setIdStore((int)$idStore)
            );
        }

        return $storeTransfers;
    }
}
