<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface getRepository()
 */
class MerchantRelationshipSalesOrderThresholdFacade extends AbstractFacade implements MerchantRelationshipSalesOrderThresholdFacadeInterface
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
        return $this->getFactory()
            ->createMerchantRelationshipThresholdReader()
            ->findApplicableThresholds($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function saveMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdWriter()
            ->saveMerchantRelationshipSalesOrderThreshold(
                $merchantRelationshipSalesOrderThresholdTransfer
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function deleteMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): bool {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdWriter()
            ->deleteMerchantRelationshipSalesOrderThreshold(
                $merchantRelationshipSalesOrderThresholdTransfer
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer[]
     */
    public function getMerchantRelationshipSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdReader()
            ->getMerchantRelationshipSalesOrderThresholds(
                $storeTransfer,
                $currencyTransfer,
                $merchantRelationshipIds
            );
    }
}
