<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Spryker\Shared\ProductManagement\ProductStatusEnum;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface;

class TableFilterFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_STATUS_CHOICES = 'status_choices';

    /**
     * @var string
     */
    public const OPTION_STORE_CHOICES = 'store_choices';

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        protected readonly ProductManagementToStoreFacadeInterface $storeFacade
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STATUS_CHOICES => $this->getStatusChoices(),
            static::OPTION_STORE_CHOICES => $this->getStoreChoices(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getStatusChoices(): array
    {
        $statusChoices = [];
        foreach (ProductStatusEnum::cases() as $case) {
            $statusChoices[ucfirst($case->value)] = $case->value;
        }

        return $statusChoices;
    }

    /**
     * @return array<string, int>
     */
    public function getStoreChoices(): array
    {
        $storeChoices = [];
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $storeChoices[$storeTransfer->getNameOrFail()] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeChoices;
    }
}
