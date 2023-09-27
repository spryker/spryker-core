<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointsRestApi\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

class CheckoutDataExpander implements CheckoutDataExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutDataWithAvailableServicePoints(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer {
        if (!$restCheckoutDataTransfer->getQuoteOrFail()->getItems()->count()) {
            return $restCheckoutDataTransfer;
        }

        $servicePointTransfers = $this->extractUniqueServicePointTransfers(
            $restCheckoutDataTransfer->getQuoteOrFail()->getItems(),
        );

        return $restCheckoutDataTransfer->setServicePoints(new ArrayObject($servicePointTransfers));
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function extractUniqueServicePointTransfers(ArrayObject $itemTransfers): array
    {
        $uniqueServicePointTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $servicePointTransfer = $itemTransfer->getServicePoint();
            if (!$servicePointTransfer || !$servicePointTransfer->getUuid()) {
                continue;
            }
            $uniqueServicePointTransfers[$servicePointTransfer->getUuidOrFail()] = $servicePointTransfer;
        }

        return array_values($uniqueServicePointTransfers);
    }
}
