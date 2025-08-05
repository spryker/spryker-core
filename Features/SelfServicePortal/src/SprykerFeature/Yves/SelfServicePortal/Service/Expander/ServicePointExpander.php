<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;

class ServicePointExpander implements ServicePointExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    protected const PARAM_SERVICE_POINT_UUID = 'service_point_uuid';

    public function __construct(protected ProductOfferStorageClientInterface $productOfferStorageClient)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithServicePoint(
        ItemTransfer $itemTransfer,
        array $params
    ): ItemTransfer {
        if (!isset($params[static::PARAM_SERVICE_POINT_UUID]) || !isset($params[static::PARAM_PRODUCT_OFFER_REFERENCE])) {
            return $itemTransfer;
        }

        $servicePointUuid = $params[static::PARAM_SERVICE_POINT_UUID];
        $productOfferReference = $params[static::PARAM_PRODUCT_OFFER_REFERENCE];

        if (!$servicePointUuid || !$productOfferReference) {
            return $itemTransfer;
        }

        $productOfferStorageTransfer = $this->productOfferStorageClient->findProductOfferStorageByReference($productOfferReference);

        if (!$productOfferStorageTransfer || !$productOfferStorageTransfer->getServices()->count()) {
            return $itemTransfer;
        }

        foreach ($productOfferStorageTransfer->getServices() as $serviceStorageTransfer) {
            if (!$serviceStorageTransfer->getServicePoint()) {
                continue;
            }

            if ($serviceStorageTransfer->getServicePoint()->getUuid() === $servicePointUuid) {
                $servicePointTransfer = (new ServicePointTransfer())->fromArray($serviceStorageTransfer->getServicePoint()->toArray(), true);

                return $itemTransfer->setServicePoint($servicePointTransfer);
            }
        }

        return $itemTransfer;
    }
}
