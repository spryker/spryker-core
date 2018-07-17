<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface getEntityManager()
 */
class MerchantRelationshipMinimumOrderValueFacade extends AbstractFacade implements MerchantRelationshipMinimumOrderValueFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Strategies\Exception\StrategyInvalidArgumentException
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
        return $this->getFactory()
            ->createMerchantRelationshipThresholdManager()
            ->setMerchantRelationshipThreshold(
                $strategyKey,
                $merchantRelationshipTransfer,
                $storeTransfer,
                $currencyTransfer,
                $thresholdValue,
                $fee
            );
    }
}
