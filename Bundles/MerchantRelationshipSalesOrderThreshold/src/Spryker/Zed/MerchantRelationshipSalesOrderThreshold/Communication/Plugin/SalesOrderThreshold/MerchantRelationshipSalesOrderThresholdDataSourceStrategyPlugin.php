<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Communication\Plugin\SalesOrderThreshold;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Communication\MerchantRelationshipSalesOrderThresholdCommunicationFactory getFactory()
 */
class MerchantRelationshipSalesOrderThresholdDataSourceStrategyPlugin extends AbstractPlugin implements SalesOrderThresholdDataSourceStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFacade()
            ->findApplicableThresholds($quoteTransfer);
    }
}
