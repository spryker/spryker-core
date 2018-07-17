<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyInvalidArgumentException;

class MerchantRelationshipThresholdManager implements MerchantRelationshipThresholdManagerInterface
{
    /**
     * @var \\Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface $minimumOrderValueFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface $entityManager
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface $minimumOrderValueFacade,
        MerchantRelationshipMinimumOrderValueEntityManagerInterface $entityManager
    ) {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        string $strategyKey,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MerchantRelationshipMinimumOrderValueTransfer {
        if (!$this->minimumOrderValueFacade->validateStrategy($strategyKey, $thresholdValue, $fee)) {
            throw new StrategyInvalidArgumentException();
        }

        $minimumOrderValueTypeTransfer = $this->minimumOrderValueFacade->getMinimumOrderValueType($strategyKey);

        return $this->entityManager
            ->setMerchantRelationshipThreshold(
                $minimumOrderValueTypeTransfer,
                $merchantRelationshipTransfer,
                $storeTransfer,
                $currencyTransfer,
                $thresholdValue,
                $fee
            );
    }
}
