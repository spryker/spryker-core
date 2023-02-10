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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>|mixed $value
     *
     * @return array<int>|null
     */
    public function transform($value): ?array
    {
        if ($value === null) {
            return null;
        }

        $storeIds = [];

        foreach ($value as $storeTransfer) {
            $idStore = $storeTransfer->getIdStoreOrFail();
            $storeIds[] = $idStore;
        }

        return $storeIds;
    }

    /**
     * @param mixed|array<int>|null $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>|null
     */
    public function reverseTransform($value): ?ArrayObject
    {
        if ($value === null) {
            return null;
        }

        $storeTransfers = new ArrayObject();

        foreach ($value as $idStore) {
            $storeTransfers->append(
                (new StoreTransfer())->setIdStore((int)$idStore),
            );
        }

        return $storeTransfers;
    }
}
