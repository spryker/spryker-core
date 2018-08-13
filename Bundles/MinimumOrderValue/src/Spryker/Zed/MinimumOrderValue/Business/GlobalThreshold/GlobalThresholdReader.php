<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface;

class GlobalThresholdReader implements GlobalThresholdReaderInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface
     */
    protected $minimumOrderValueRepository;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface $minimumOrderValueRepository
     */
    public function __construct(
        MinimumOrderValueRepositoryInterface $minimumOrderValueRepository
    ) {
        $this->minimumOrderValueRepository = $minimumOrderValueRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        return $this->minimumOrderValueRepository
            ->getGlobalThresholdsByStoreAndCurrency(
                $storeTransfer,
                $currencyTransfer
            );
    }
}
