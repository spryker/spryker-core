<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer>
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdReader()
            ->findApplicableThresholds($quoteTransfer);
    }

    /**
     * {@inheritDoc}
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
                $merchantRelationshipSalesOrderThresholdTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return bool
     */
    public function deleteMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): bool {
        return $this->getFactory()
            ->createMerchantRelationshipThresholdWriter()
            ->deleteMerchantRelationshipSalesOrderThreshold(
                $merchantRelationshipSalesOrderThresholdTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param array<int> $merchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer>
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
                $merchantRelationshipIds,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    public function deleteMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdDeleter()
            ->deleteMerchantRelationshipSalesOrderThresholdCollection(
                $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     */
    public function mapMerchantRelationshipToDeleteThresholdCollectionCriteria(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer {
        return $this->getFactory()
            ->createMerchantRelationshipSalesOrderThresholdMapper()
            ->mapMerchantRelationshipToDeleteThresholdCollectionCriteria(
                $merchantRelationshipTransfer,
            );
    }
}
