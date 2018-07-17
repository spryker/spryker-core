<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeBridge implements MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacadeInterface
     */
    protected $merchantRelationshipMinimumOrderValueFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacadeInterface $merchantRelationshipMinimumOrderValueFacade
     */
    public function __construct($merchantRelationshipMinimumOrderValueFacade)
    {
        $this->merchantRelationshipMinimumOrderValueFacade = $merchantRelationshipMinimumOrderValueFacade;
    }

    /**
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        string $strategyKey,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $value,
        ?int $fee = null
    ): MerchantRelationshipMinimumOrderValueTransfer {
        return $this->merchantRelationshipMinimumOrderValueFacade->setMerchantRelationshipThreshold(
            $strategyKey,
            $merchantRelationshipTransfer,
            $storeTransfer,
            $currencyTransfer,
            $value,
            $fee
        );
    }
}
