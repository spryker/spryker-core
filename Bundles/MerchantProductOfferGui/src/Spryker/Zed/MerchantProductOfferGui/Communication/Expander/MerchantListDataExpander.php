<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\MerchantProductOfferGui\Dependency\Facade\MerchantProductOfferGuiToMerchantFacadeInterface;

class MerchantListDataExpander implements MerchantListDataExpanderInterface
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
     * @phpstan-param array<string, mixed> $viewData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $viewData
     *
     * @return array
     */
    public function expandData(array $viewData): array
    {
        $viewData['merchants'] = [];
        $merchantCollectionTransfer = $this->merchantFacade
            ->get(new MerchantCriteriaTransfer());

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $viewData['merchants'][$merchantTransfer->getIdMerchant()] = $merchantTransfer;
        }

        return $viewData;
    }
}
