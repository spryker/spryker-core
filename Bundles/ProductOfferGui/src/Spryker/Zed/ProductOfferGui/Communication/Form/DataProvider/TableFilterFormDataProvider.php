<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Shared\ProductOfferGui\ProductOfferApprovalStatusEnum;
use Spryker\Shared\ProductOfferGui\ProductOfferStatusEnum;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToStoreFacadeInterface;

class TableFilterFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_STATUS_CHOICES = 'status_choices';

    /**
     * @var string
     */
    public const OPTION_APPROVAL_STATUS_CHOICES = 'approval_status_choices';

    /**
     * @var string
     */
    public const OPTION_STORE_CHOICES = 'store_choices';

    /**
     * @param \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        protected readonly ProductOfferGuiToProductOfferFacadeInterface $productOfferFacade,
        protected readonly ProductOfferGuiToStoreFacadeInterface $storeFacade
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STATUS_CHOICES => $this->getStatusChoices(),
            static::OPTION_APPROVAL_STATUS_CHOICES => $this->getApprovalStatusChoices(),
            static::OPTION_STORE_CHOICES => $this->getStoreChoices(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getStatusChoices(): array
    {
        $statusChoices = [];
        foreach (ProductOfferStatusEnum::cases() as $case) {
            $statusChoices[ucfirst($case->value)] = $case->value;
        }

        return $statusChoices;
    }

    /**
     * @return array<string, string>
     */
    public function getApprovalStatusChoices(): array
    {
        return array_flip(ProductOfferApprovalStatusEnum::getOptionsArray());
    }

    /**
     * @return array<string, int>
     */
    public function getStoreChoices(): array
    {
        $storeChoices = [];
        $storeCollectionTransfer = $this->storeFacade->getStoreCollection(new StoreCriteriaTransfer());

        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeChoices[$storeTransfer->getName()] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeChoices;
    }
}
