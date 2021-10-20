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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>|null $storeTransfers
     *
     * @return array<int>|null
     */
    public function transform($storeTransfers): ?array
    {
        if ($storeTransfers === null) {
            return null;
        }

        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            /** @var int $idStore */
            $idStore = $storeTransfer->requireIdStore()->getIdStore();
            $storeIds[] = $idStore;
        }

        return $storeIds;
    }

    /**
     * @param array<int>|null $storeIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>|null
     */
    public function reverseTransform($storeIds): ?ArrayObject
    {
        if ($storeIds === null) {
            return null;
        }

        $storeTransfers = new ArrayObject();

        foreach ($storeIds as $idStore) {
            $storeTransfers->append(
                (new StoreTransfer())->setIdStore((int)$idStore),
            );
        }

        return $storeTransfers;
    }
}
