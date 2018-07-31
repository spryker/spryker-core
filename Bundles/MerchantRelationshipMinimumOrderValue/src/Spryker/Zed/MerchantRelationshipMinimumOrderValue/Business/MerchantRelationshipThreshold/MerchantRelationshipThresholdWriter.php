<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface;

class MerchantRelationshipThresholdWriter implements MerchantRelationshipThresholdWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface
     */
    protected $merchantRelationshipMinimumOrderValueEntityManager;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface $minimumOrderValueFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface $merchantRelationshipMinimumOrderValueEntityManager
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface $minimumOrderValueFacade,
        MerchantRelationshipMinimumOrderValueEntityManagerInterface $merchantRelationshipMinimumOrderValueEntityManager
    ) {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
        $this->merchantRelationshipMinimumOrderValueEntityManager = $merchantRelationshipMinimumOrderValueEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $this->minimumOrderValueFacade->isStrategyValid(
            $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueType(),
            $merchantRelationshipMinimumOrderValueTransfer->getValue(),
            $merchantRelationshipMinimumOrderValueTransfer->getFee()
        );

        $merchantRelationshipMinimumOrderValueTransfer->setMinimumOrderValueType(
            $this->minimumOrderValueFacade
                ->getMinimumOrderValueType($merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueType())
        );

        return $this->merchantRelationshipMinimumOrderValueEntityManager
            ->setMerchantRelationshipThreshold($merchantRelationshipMinimumOrderValueTransfer);
    }
}
