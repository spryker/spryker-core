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
     * @phpstan-param array<\Generated\Shared\Transfer\StoreTransfer>|null $storeTransfers
     *
     * @param \Generated\Shared\Transfer\StoreTransfer[]|\ArrayObject|null $storeTransfers
     *
     * @return int[]|null
     */
    public function transform($storeTransfers): ?array
    {
        if ($storeTransfers === null) {
            return null;
        }

        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            $idStore = $storeTransfer->getIdStore();

            if (!$idStore) {
                continue;
            }

            $storeIds[] = $idStore;
        }

        return $storeIds;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>|null
     *
     * @param int[]|null $storeIds
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]|\ArrayObject|null
     */
    public function reverseTransform($storeIds): ?ArrayObject
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
