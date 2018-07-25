<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;

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
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        return $this->merchantRelationshipMinimumOrderValueFacade->setMerchantRelationshipThreshold(
            $merchantRelationshipMinimumOrderValueTransfer
        );
    }
}
