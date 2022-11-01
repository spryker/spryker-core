<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Communication\MerchantRelationshipSalesOrderThresholdCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdConfig getConfig()
 */
class MerchantRelationshipSalesOrderThresholdMerchantRelationshipPreDeletePlugin extends AbstractPlugin implements MerchantRelationshipPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Deletes collection of MerchantRelationshipSalesOrderThresholds by delete criteria.
     * - Deactivates all localized messages and glossary keys for merchant relationship sales order thresholds.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->getFacade()->deleteMerchantRelationshipSalesOrderThresholdCollection(
            $this->getFacade()->mapMerchantRelationshipToDeleteThresholdCollectionCriteria($merchantRelationshipTransfer),
        );
    }
}
