<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Writer;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesReturn\Business\Expander\ReturnExpanderInterface;
use Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface;

class ReturnWriter implements ReturnWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface
     */
    protected $salesReturnEntityManager;

    /**
     * @var \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface
     */
    protected $returnValidator;

    /**
     * @var \Spryker\Zed\SalesReturn\Business\Expander\ReturnExpanderInterface
     */
    protected $returnExpander;

    /**
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface $salesReturnEntityManager
     * @param \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface $returnValidator
     * @param \Spryker\Zed\SalesReturn\Business\Expander\ReturnExpanderInterface $returnExpander
     */
    public function __construct(
        SalesReturnEntityManagerInterface $salesReturnEntityManager,
        ReturnValidatorInterface $returnValidator,
        ReturnExpanderInterface $returnExpander
    ) {
        $this->salesReturnEntityManager = $salesReturnEntityManager;
        $this->returnValidator = $returnValidator;
        $this->returnExpander = $returnExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        $this->assertReturnRequirements($createReturnRequestTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($createReturnRequestTransfer) {
            return $this->executeCreateReturnTransaction($createReturnRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function executeCreateReturnTransaction(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        $returnResponseTransfer = $this->returnValidator->validateReturnRequest($createReturnRequestTransfer);

        if (!$returnResponseTransfer->getIsSuccessful()) {
            return $returnResponseTransfer;
        }

        $returnTransfer = $this->createReturnTransfer($createReturnRequestTransfer);
        $returnTransfer = $this->createReturnItemTransfers($returnTransfer);

        $returnTransfer = $this->returnExpander->expandReturn($returnTransfer);

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true)
            ->setReturn($returnTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    protected function createReturnTransfer(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnTransfer
    {
        $returnTransfer = (new ReturnTransfer())
            ->setStore($createReturnRequestTransfer->getStore())
            ->setCustomer($createReturnRequestTransfer->getCustomer())
            ->setReturnItems($createReturnRequestTransfer->getReturnItems());

        return $this->salesReturnEntityManager->createReturn($returnTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    protected function createReturnItemTransfers(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnTransfer->requireIdSalesReturn();

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $returnItemTransfer
                ->requireOrderItem()
                ->getOrderItem()
                    ->requireIdSalesOrderItem();

            $returnItemTransfer->setIdSalesReturn($returnTransfer->getIdSalesReturn());
            $this->salesReturnEntityManager->createReturnItem($returnItemTransfer);
        }

        return $returnTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return void
     */
    protected function assertReturnRequirements(CreateReturnRequestTransfer $createReturnRequestTransfer): void
    {
        $createReturnRequestTransfer
            ->requireReturnItems()
            ->requireStore()
            ->requireCustomer()
            ->getCustomer()
                ->requireCustomerReference();
    }
}
