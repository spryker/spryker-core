<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTransfer;

/**
 * @implements \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>, array<string>>
 */
class ServicePointServicesDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>|mixed $serviceTransfers
     *
     * @return array<string>
     */
    public function transform(mixed $serviceTransfers): array
    {
        if (!$serviceTransfers) {
            return [];
        }

        $serviceIds = [];
        foreach ($serviceTransfers->getArrayCopy() as $serviceTransfer) {
            if ($serviceTransfer->getIdService() !== null) {
                $serviceIds[] = (string)$serviceTransfer->getIdService();
            }
        }

        return $serviceIds;
    }

    /**
     * @param mixed|array<string>|null $serviceUuids
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>
     */
    public function reverseTransform(mixed $serviceUuids): ArrayObject
    {
        if (!$serviceUuids) {
            return new ArrayObject();
        }

        $serviceTransfers = new ArrayObject();
        foreach ($serviceUuids as $serviceUuid) {
            if ($serviceUuid === null) {
                continue;
            }

            $serviceTransfers->append(
                (new ServiceTransfer())->setUuid($serviceUuid),
            );
        }

        return $serviceTransfers;
    }
}
