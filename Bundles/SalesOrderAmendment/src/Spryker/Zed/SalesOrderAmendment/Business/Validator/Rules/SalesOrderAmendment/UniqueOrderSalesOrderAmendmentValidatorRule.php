<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface;

class UniqueOrderSalesOrderAmendmentValidatorRule implements SalesOrderAmendmentValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DUPLICATED = 'sales_order_amendment.validation.order_amendment_duplicated';

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface
     */
    protected SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository;

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository,
        ErrorAdderInterface $errorAdder
    ) {
        $this->salesOrderAmendmentRepository = $salesOrderAmendmentRepository;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        if ($this->hasSalesOrderAmendment($salesOrderAmendmentTransfer)) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DUPLICATED,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return bool
     */
    protected function hasSalesOrderAmendment(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): bool
    {
        $salesOrderAmendmentConditionsTransfer = (new SalesOrderAmendmentConditionsTransfer())
            ->addAmendmentOrderReference($salesOrderAmendmentTransfer->getAmendmentOrderReferenceOrFail());
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer);

        $salesOrderAmendmentTransfers = $this->salesOrderAmendmentRepository
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer)
            ->getSalesOrderAmendments();

        if ($salesOrderAmendmentTransfer->getUuid() === null) {
            return $salesOrderAmendmentTransfers->count() > 0;
        }

        return !$this->isSameSalesOrderAmendment(
            $salesOrderAmendmentTransfer,
            $salesOrderAmendmentTransfers->getIterator()->current(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $providedSalesOrderAmendmentTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $persistedSalesOrderAmendmentTransfer
     *
     * @return bool
     */
    protected function isSameSalesOrderAmendment(
        SalesOrderAmendmentTransfer $providedSalesOrderAmendmentTransfer,
        SalesOrderAmendmentTransfer $persistedSalesOrderAmendmentTransfer
    ): bool {
        return $providedSalesOrderAmendmentTransfer->getUuidOrFail() === $persistedSalesOrderAmendmentTransfer->getUuidOrFail();
    }
}
