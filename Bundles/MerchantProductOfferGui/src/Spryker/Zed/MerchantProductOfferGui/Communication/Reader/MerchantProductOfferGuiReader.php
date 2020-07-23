<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface;

class MerchantProductOfferGuiReader implements MerchantProductOfferGuiReaderInterface
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
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getMerchantData(ProductOfferTransfer $productOfferTransfer): array
    {
        $productOfferTransfer->requireFkMerchant();

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setIdMerchant($productOfferTransfer->getFkMerchant());

        $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);

        if (!$merchantTransfer) {
            $merchantTransfer = new MerchantTransfer();
        }

        return array_merge($merchantTransfer->toArray(), [
            'sku' => $productOfferTransfer->getMerchantSku(),
        ]);
    }
}
