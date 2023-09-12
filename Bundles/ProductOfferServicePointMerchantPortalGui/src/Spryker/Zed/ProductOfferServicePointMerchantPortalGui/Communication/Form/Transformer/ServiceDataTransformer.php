<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class ServiceDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>|mixed $value
     *
     * @return list<string>|null
     */
    public function transform(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        $serviceUuids = [];
        foreach ($value as $serviceTransfer) {
            $serviceUuids[] = $serviceTransfer->getUuidOrFail();
        }

        return $serviceUuids;
    }

    /**
     * @param list<string>|mixed|null $value
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>|null
     */
    public function reverseTransform(mixed $value): ?ArrayObject
    {
        if ($value === null) {
            return null;
        }

        $serviceTransfers = new ArrayObject();
        foreach ($value as $serviceUuid) {
            $serviceTransfers->append(
                (new ServiceTransfer())->setUuid($serviceUuid),
            );
        }

        return $serviceTransfers;
    }
}
