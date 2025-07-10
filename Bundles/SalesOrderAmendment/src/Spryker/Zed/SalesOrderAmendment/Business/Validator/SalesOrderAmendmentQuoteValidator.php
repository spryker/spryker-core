<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface;

class SalesOrderAmendmentQuoteValidator implements SalesOrderAmendmentQuoteValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_NOT_FOUND_ENTITY = 'Entity with ID `%d` was not found in the database.';

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
     */
    public function __construct(
        protected SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
    ) {
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer> $salesOrderAmendmentQuoteTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function validate(
        ArrayObject $salesOrderAmendmentQuoteTransfers
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer {
        $salesOrderAmendmentQuoteCollectionResponseTransfer = new SalesOrderAmendmentQuoteCollectionResponseTransfer();

        $salesOrderAmendmentQuoteIds = $this->getSalesOrderAmendmentQuoteIds($salesOrderAmendmentQuoteTransfers);
        $persistedSalesOrderAmendmentQuoteIds = $this->getPersistedSalesOrderAmendmentQuoteIds($salesOrderAmendmentQuoteIds);

        $notExistingSalesOrderAmendmentQuoteIds = array_diff($salesOrderAmendmentQuoteIds, $persistedSalesOrderAmendmentQuoteIds);
        if ($notExistingSalesOrderAmendmentQuoteIds) {
            $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->addErrorsForNonExistingQuotes(
                $salesOrderAmendmentQuoteCollectionResponseTransfer,
                $notExistingSalesOrderAmendmentQuoteIds,
            );
        }

        return $salesOrderAmendmentQuoteCollectionResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer> $salesOrderAmendmentQuoteTransfers
     *
     * @return array<int>
     */
    protected function getSalesOrderAmendmentQuoteIds(ArrayObject $salesOrderAmendmentQuoteTransfers): array
    {
        $salesOrderAmendmentQuoteIds = [];
        foreach ($salesOrderAmendmentQuoteTransfers as $salesOrderAmendmentQuoteTransfer) {
            $salesOrderAmendmentQuoteIds[] = $salesOrderAmendmentQuoteTransfer->getIdSalesOrderAmendmentQuoteOrFail();
        }

        return $salesOrderAmendmentQuoteIds;
    }

    /**
     * @param array<int> $salesOrderAmendmentQuoteIds
     *
     * @return array<int>
     */
    protected function getPersistedSalesOrderAmendmentQuoteIds(array $salesOrderAmendmentQuoteIds): array
    {
        $salesOrderAmendmentQuoteCollectionTransfer = $this->salesOrderAmendmentRepository->getSalesOrderAmendmentQuoteCollection(
            (new SalesOrderAmendmentQuoteCriteriaTransfer())->setSalesOrderAmendmentQuoteConditions(
                (new SalesOrderAmendmentQuoteConditionsTransfer())
                    ->setSalesOrderAmendmentQuoteIds($salesOrderAmendmentQuoteIds),
            ),
        );

        return $this->getSalesOrderAmendmentQuoteIds(
            $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer $salesOrderAmendmentQuoteCollectionResponseTransfer
     * @param array<int> $notExistingSalesOrderAmendmentQuoteIds
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    protected function addErrorsForNonExistingQuotes(
        SalesOrderAmendmentQuoteCollectionResponseTransfer $salesOrderAmendmentQuoteCollectionResponseTransfer,
        array $notExistingSalesOrderAmendmentQuoteIds
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer {
        foreach ($notExistingSalesOrderAmendmentQuoteIds as $notExistingSalesOrderAmendmentQuoteId) {
            $salesOrderAmendmentQuoteCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_NOT_FOUND_ENTITY, $notExistingSalesOrderAmendmentQuoteId)),
            );
        }

        return $salesOrderAmendmentQuoteCollectionResponseTransfer;
    }
}
