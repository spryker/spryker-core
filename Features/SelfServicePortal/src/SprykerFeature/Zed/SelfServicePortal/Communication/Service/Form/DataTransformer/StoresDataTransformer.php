<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * @implements \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>, array<int>|null>
 */
class StoresDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>|mixed $storeTransfers
     *
     * @return array<int>|null
     */
    public function transform(mixed $storeTransfers): ?array
    {
        if (!$storeTransfers) {
            return null;
        }

        $storeIds = [];
        foreach ($storeTransfers as $storeTransfer) {
            if ($storeTransfer->getIdStore() !== null) {
                $storeIds[] = $storeTransfer->getIdStore();
            }
        }

        return $storeIds;
    }

    /**
     * @param mixed|array<int>|null $storeIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    public function reverseTransform(mixed $storeIds): ArrayObject
    {
        if (!$storeIds) {
            return new ArrayObject();
        }

        $storeTransfers = new ArrayObject();
        foreach ($storeIds as $idStore) {
            if ($idStore === null) {
                continue;
            }

            $storeTransfers->append((new StoreTransfer())
                ->setIdStore($idStore));
        }

        return $storeTransfers;
    }
}
