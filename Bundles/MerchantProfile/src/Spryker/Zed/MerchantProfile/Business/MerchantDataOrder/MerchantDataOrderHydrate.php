<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantDataOrder;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\OrderMerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface;

class MerchantDataOrderHydrate implements MerchantDataOrderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface
     */
    protected MerchantProfileRepositoryInterface $merchantProfileRepository;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface $merchantProfileRepository
     */
    public function __construct(MerchantProfileRepositoryInterface $merchantProfileRepository)
    {
        $this->merchantProfileRepository = $merchantProfileRepository;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithMerchantData(OrderTransfer $orderTransfer): OrderTransfer
    {
        if (!$orderTransfer->getMerchantReferences()) {
            return $orderTransfer;
        }

        $merchantProfileCriteriaTransfer = new MerchantProfileCriteriaTransfer();

        $merchantProfileCriteriaTransfer->setMerchantReferences(
            $orderTransfer->getMerchantReferences(),
        );

        $merchantProfilesCollection = $this->merchantProfileRepository->get($merchantProfileCriteriaTransfer);

        $merchantsList = [];
        foreach ($merchantProfilesCollection->getMerchantProfiles() as $merchantProfile) {
            $orderMerchantTransfer = new OrderMerchantTransfer();

            $orderMerchantTransfer->setMerchantReference($merchantProfile->getMerchantReference());
            $orderMerchantTransfer->setName($merchantProfile->getMerchantName());
            $orderMerchantTransfer->setImageUrl($merchantProfile->getLogoUrl());

            $merchantsList[] = $orderMerchantTransfer;
        }

        $orderTransfer->setMerchants(new ArrayObject($merchantsList));

        return $orderTransfer;
    }
}
