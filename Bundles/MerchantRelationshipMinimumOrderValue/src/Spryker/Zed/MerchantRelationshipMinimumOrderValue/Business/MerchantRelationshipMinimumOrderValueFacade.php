<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdReader()
            ->findApplicableThresholds($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdWriter()
            ->setMerchantRelationshipThreshold(
                $merchantRelationshipMinimumOrderValueTransfer
            );
    }
}
