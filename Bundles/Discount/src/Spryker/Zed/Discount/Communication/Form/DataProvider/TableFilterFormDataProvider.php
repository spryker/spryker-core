<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DiscountTableCriteriaTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;

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
    public const OPTION_TYPES = 'types';

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        protected DiscountToStoreFacadeInterface $storeFacade
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTableCriteriaTransfer
     */
    public function getData(): DiscountTableCriteriaTransfer
    {
        return new DiscountTableCriteriaTransfer();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STATUSES => $this->getStatusChoices(),
            static::OPTION_TYPES => $this->getTypeChoices(),
            static::OPTION_STORES => $this->getStoreChoices(),
        ];
    }

    /**
     * @return array<string, int>
     */
    protected function getStatusChoices(): array
    {
        return [
            'Active' => 1,
            'Inactive' => 0,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getTypeChoices(): array
    {
        return [
            'Cart rule' => DiscountConstants::TYPE_CART_RULE,
            'Voucher codes' => DiscountConstants::TYPE_VOUCHER,
        ];
    }

    /**
     * @return array<string, int>
     */
    protected function getStoreChoices(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $storeChoices = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeChoices[$storeTransfer->getNameOrFail()] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeChoices;
    }
}
