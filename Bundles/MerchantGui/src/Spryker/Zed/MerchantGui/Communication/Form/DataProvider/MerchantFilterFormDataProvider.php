<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToStoreFacadeInterface;

class MerchantFilterFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_STORES = 'stores';

    /**
     * @var string
     */
    public const OPTION_APPROVAL_STATUSES = 'approval_statuses';

    /**
     * @var string
     */
    public const OPTION_STATUSES = 'statuses';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     *
     * @var array<string, string>
     */
    protected const AVAILABLE_APPROVAL_STATUSES = [
        'waiting-for-approval' => 'waiting-for-approval',
        'approved' => 'approved',
        'denied' => 'denied',
    ];

    /**
     * @var array<string, int>
     */
    protected const AVAILABLE_STATUSES = [
        'active' => 1,
        'inactive' => 0,
    ];

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STATUSES => static::AVAILABLE_STATUSES,
            static::OPTION_APPROVAL_STATUSES => static::AVAILABLE_APPROVAL_STATUSES,
            static::OPTION_STORES => $this->getStoreChoices(),
        ];
    }

    /**
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(protected MerchantGuiToStoreFacadeInterface $storeFacade)
    {
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
