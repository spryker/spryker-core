<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Communication\Plugin\MinimumOrderValue;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Communication\MerchantRelationshipMinimumOrderValueCommunicationFactory getFactory()
 */
class MerchantRelationshipMinimumOrderValueDataSourceStrategyPlugin extends AbstractPlugin implements MinimumOrderValueDataSourceStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFacade()
            ->findApplicableThresholds($quoteTransfer);
    }
}
