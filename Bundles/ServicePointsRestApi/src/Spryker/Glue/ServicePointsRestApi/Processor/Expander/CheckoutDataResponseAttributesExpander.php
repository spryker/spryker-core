<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestServicePointTransfer;

class CheckoutDataResponseAttributesExpander implements CheckoutDataResponseAttributesExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function expandCheckoutDataResponseAttributesWithSelectedServicePoints(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $itemGroupKeysGroupedByServicePointUuid = $this->getItemGroupKeysGroupedByServicePointUuid($restCheckoutDataTransfer);

        foreach ($itemGroupKeysGroupedByServicePointUuid as $servicePointUuid => $itemGroupKeys) {
            $restCheckoutResponseAttributesTransfer->addSelectedServicePoint(
                $this->createRestServicePointTransfer($servicePointUuid, $itemGroupKeys),
            );
        }

        return $restCheckoutResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return array<string, list<string>>
     */
    protected function getItemGroupKeysGroupedByServicePointUuid(RestCheckoutDataTransfer $restCheckoutDataTransfer): array
    {
        $itemGroupKeysGroupedByServicePointUuid = [];

        foreach ($restCheckoutDataTransfer->getQuoteOrFail()->getItems() as $itemTransfer) {
            if (!$itemTransfer->getServicePoint() || !$itemTransfer->getServicePoint()->getUuid()) {
                continue;
            }
            $itemGroupKeysGroupedByServicePointUuid[$itemTransfer->getServicePointOrFail()->getUuid()][] = $itemTransfer->getGroupKeyOrFail();
        }

        return $itemGroupKeysGroupedByServicePointUuid;
    }

    /**
     * @param string $servicePointUuid
     * @param list<string> $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\RestServicePointTransfer
     */
    protected function createRestServicePointTransfer(
        string $servicePointUuid,
        array $itemGroupKeys
    ): RestServicePointTransfer {
        return (new RestServicePointTransfer())
            ->setIdServicePoint($servicePointUuid)
            ->setItems($itemGroupKeys);
    }
}
