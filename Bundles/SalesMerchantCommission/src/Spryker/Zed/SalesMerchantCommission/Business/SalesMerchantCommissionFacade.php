<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionEntityManagerInterface getEntityManager()
 */
class SalesMerchantCommissionFacade extends AbstractFacade implements SalesMerchantCommissionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function getSalesMerchantCommissionCollection(
        SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
    ): SalesMerchantCommissionCollectionTransfer {
        return $this->getRepository()->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createSalesMerchantCommissions(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createSalesMerchantCommissionCreator()
            ->createSalesMerchantCommissions($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateMerchantCommissions(
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer {
        return $this->getFactory()
            ->createMerchantCommissionCalculator()
            ->recalculateMerchantCommissions($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function refundMerchantCommissions(OrderTransfer $orderTransfer, array $itemTransfers): OrderTransfer
    {
        return $this->getFactory()
            ->createMerchantCommissionRefunder()
            ->refundMerchantCommissions($orderTransfer, $itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeMerchantCommissionFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createMerchantCommissionQuoteSanitizer()
            ->sanitizeMerchantCommissionFromQuote($quoteTransfer);
    }
}
