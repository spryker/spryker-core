<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Reader;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface;

class SalesOrderAmendmentQuoteReader implements SalesOrderAmendmentQuoteReaderInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentQuoteExpanderPluginInterface> $salesOrderAmendmentQuoteExpanderPlugins
     */
    public function __construct(
        protected SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository,
        protected array $salesOrderAmendmentQuoteExpanderPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function getSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer {
        $salesOrderAmendmentQuoteCollectionTransfer = $this->salesOrderAmendmentRepository
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        return $salesOrderAmendmentQuoteCriteriaTransfer->getWithExpanderPlugins()
            ? $this->executeSalesOrderAmendmentQuoteExpanderPlugins($salesOrderAmendmentQuoteCollectionTransfer)
            : $salesOrderAmendmentQuoteCollectionTransfer;
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null
     */
    public function findSalesOrderAmendmentQuoteByOrderReference(string $orderReference): ?SalesOrderAmendmentQuoteTransfer
    {
        $salesOrderAmendmentQuoteCriteriaTransfer = (new SalesOrderAmendmentQuoteCriteriaTransfer())
            ->setSalesOrderAmendmentQuoteConditions(
                (new SalesOrderAmendmentQuoteConditionsTransfer())->addAmendmentOrderReference(
                    $orderReference,
                ),
            );

        $salesOrderAmendmentQuoteCollectionTransfer = $this->salesOrderAmendmentRepository
            ->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);

        return $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()
            ->getIterator()
            ->current();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    protected function executeSalesOrderAmendmentQuoteExpanderPlugins(
        SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer {
        foreach ($this->salesOrderAmendmentQuoteExpanderPlugins as $salesOrderAmendmentQuoteExpanderPlugin) {
            $salesOrderAmendmentQuoteCollectionTransfer = $salesOrderAmendmentQuoteExpanderPlugin->expand(
                $salesOrderAmendmentQuoteCollectionTransfer,
            );
        }

        return $salesOrderAmendmentQuoteCollectionTransfer;
    }
}
