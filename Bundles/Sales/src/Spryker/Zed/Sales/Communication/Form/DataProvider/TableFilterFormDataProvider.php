<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Generated\Shared\Transfer\OrderTableCriteriaTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class TableFilterFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_STORES = 'stores';

    /**
     * @var string
     */
    public const OPTION_STATUSES = 'statuses';

    /**
     * @var string
     */
    public const OPTION_CURRENT_TIMEZONE = 'current_timezone';

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface $storeFacade
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(
        protected SalesQueryContainerInterface $queryContainer,
        protected SalesToStoreInterface $storeFacade,
        protected SalesRepositoryInterface $salesRepository
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTableCriteriaTransfer
     */
    public function getData(): OrderTableCriteriaTransfer
    {
        return new OrderTableCriteriaTransfer();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STATUSES => $this->getStatusChoices(),
            static::OPTION_STORES => $this->getStoreChoices(),
            static::OPTION_CURRENT_TIMEZONE => $this->storeFacade->getCurrentStore(true)->getTimezone(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getStatusChoices(): array
    {
        $statusChoices = [];
        $omsOrderItemStates = $this->salesRepository->getOmsOrderItemStates();

        foreach ($omsOrderItemStates as $omsOrderItemState) {
            $statusChoices[$omsOrderItemState] = $omsOrderItemState;
        }

        return $statusChoices;
    }

    /**
     * @return array<string, string>
     */
    protected function getStoreChoices(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $storeChoices = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeName = $storeTransfer->getNameOrFail();
            $storeChoices[$storeName] = $storeName;
        }

        return $storeChoices;
    }
}
