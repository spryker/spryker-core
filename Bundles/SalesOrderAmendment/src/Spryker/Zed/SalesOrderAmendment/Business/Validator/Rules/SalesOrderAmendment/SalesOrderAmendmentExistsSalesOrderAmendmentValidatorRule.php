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

class SalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule implements SalesOrderAmendmentValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DOES_NOT_EXIST = 'sales_order_amendment.validation.sales_order_amendment_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_UUID = '%uuid%';

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(protected SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository, protected ErrorAdderInterface $errorAdder)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        if (!$this->salesOrderAmendmentExists($salesOrderAmendmentTransfer)) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_AMENDMENT_DOES_NOT_EXIST,
                [
                    static::GLOSSARY_KEY_PARAMETER_UUID => $salesOrderAmendmentTransfer->getUuidOrFail(),
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return bool
     */
    protected function salesOrderAmendmentExists(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): bool
    {
        $salesOrderAmendmentConditionsTransfer = (new SalesOrderAmendmentConditionsTransfer())
            ->addUuid($salesOrderAmendmentTransfer->getUuidOrFail());
        $salesOrderAmendmentCriteriaTransfer = (new SalesOrderAmendmentCriteriaTransfer())
            ->setSalesOrderAmendmentConditions($salesOrderAmendmentConditionsTransfer);

        $salesOrderAmendmentTransfers = $this->salesOrderAmendmentRepository
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer)
            ->getSalesOrderAmendments();

        return $salesOrderAmendmentTransfers->count() === 1;
    }
}
