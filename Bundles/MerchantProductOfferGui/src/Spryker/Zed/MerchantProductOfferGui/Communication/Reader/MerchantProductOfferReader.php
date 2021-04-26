<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface;

class MerchantProductOfferReader implements MerchantProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantProductOfferGuiToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getMerchantData(ProductOfferTransfer $productOfferTransfer): array
    {
        $merchantName = null;
        $merchantId = null;

        if ($productOfferTransfer->getMerchantReference()) {
            $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
                ->setMerchantReference($productOfferTransfer->getMerchantReference());

            $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);

            if ($merchantTransfer !== null) {
                $merchantName = $merchantTransfer->getName();
                $merchantId = $merchantTransfer->getIdMerchant();
            }
        }

        return [
            'idMerchant' => (string)$merchantId,
            'merchantName' => (string)$merchantName,
            'merchantSku' => $productOfferTransfer->getMerchantSku(),
        ];
    }
}
