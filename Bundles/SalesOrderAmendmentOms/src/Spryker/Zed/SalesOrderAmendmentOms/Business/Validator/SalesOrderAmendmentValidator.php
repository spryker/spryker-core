<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface;

class SalesOrderAmendmentValidator implements SalesOrderAmendmentValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_AMENDMENT_ORDER_DOES_NOT_EXIST = 'sales_order_amendment_oms.validation.amendment_order_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_AMENDED_ORDER_DOES_NOT_EXIST = 'sales_order_amendment_oms.validation.amended_order_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_ORDER_REFERENCE = '%order_reference%';

    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface
     */
    protected OrderReaderInterface $orderReader;

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface $orderReader
     */
    public function __construct(OrderReaderInterface $orderReader)
    {
        $this->orderReader = $orderReader;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = $this->validateAmendedOrderReference(
            $salesOrderAmendmentTransfer,
            new ErrorCollectionTransfer(),
        );

        return $this->validateAmendmentOrderReference($salesOrderAmendmentTransfer, $errorCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateAmendmentOrderReference(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        $orderReference = $salesOrderAmendmentTransfer->getAmendmentOrderReferenceOrFail();
        if (!$this->orderExists($orderReference)) {
            $this->addError(
                $errorCollectionTransfer,
                static::GLOSSARY_KEY_VALIDATION_AMENDMENT_ORDER_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_ORDER_REFERENCE => $orderReference],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateAmendedOrderReference(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        $orderReference = $salesOrderAmendmentTransfer->getAmendedOrderReferenceOrFail();
        if (!$this->orderExists($orderReference)) {
            $this->addError(
                $errorCollectionTransfer,
                static::GLOSSARY_KEY_VALIDATION_AMENDED_ORDER_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_ORDER_REFERENCE => $orderReference],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $orderReference
     *
     * @return bool
     */
    protected function orderExists(string $orderReference): bool
    {
        return $this->orderReader->findOrderByOrderReference($orderReference) !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param string $error
     * @param array<string, int|string> $params
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addError(
        ErrorCollectionTransfer $errorCollectionTransfer,
        string $error,
        array $params = []
    ): ErrorCollectionTransfer {
        return $errorCollectionTransfer->addError(
            $this->createErrorTransfer($error, $params),
        );
    }

    /**
     * @param string $error
     * @param array<string, int|string> $parameters
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(
        string $error,
        array $parameters = []
    ): ErrorTransfer {
        return (new ErrorTransfer())
            ->setMessage($error)
            ->setParameters($parameters);
    }
}
