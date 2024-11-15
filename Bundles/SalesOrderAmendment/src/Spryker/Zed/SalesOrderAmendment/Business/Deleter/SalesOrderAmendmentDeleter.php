<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Deleter;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface;

class SalesOrderAmendmentDeleter implements SalesOrderAmendmentDeleterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_SALES_ORDER_AMENDMENT_NOT_FOUND = 'sales_order_amendment.error.not_found';

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface
     */
    protected SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager;

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface
     */
    protected SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository;

    /**
     * @var list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreDeletePluginInterface>
     */
    protected array $salesOrderAmendmentPreDeletePlugins;

    /**
     * @var list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostDeletePluginInterface>
     */
    protected array $salesOrderAmendmentPostDeletePlugins;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreDeletePluginInterface> $salesOrderAmendmentPreDeletePlugins
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostDeletePluginInterface> $salesOrderAmendmentPostDeletePlugins
     */
    public function __construct(
        SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager,
        SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository,
        array $salesOrderAmendmentPreDeletePlugins,
        array $salesOrderAmendmentPostDeletePlugins
    ) {
        $this->salesOrderAmendmentEntityManager = $salesOrderAmendmentEntityManager;
        $this->salesOrderAmendmentRepository = $salesOrderAmendmentRepository;
        $this->salesOrderAmendmentPreDeletePlugins = $salesOrderAmendmentPreDeletePlugins;
        $this->salesOrderAmendmentPostDeletePlugins = $salesOrderAmendmentPostDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    public function deleteSalesOrderAmendment(
        SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
    ): SalesOrderAmendmentResponseTransfer {
        $salesOrderAmendmentResponseTransfer = new SalesOrderAmendmentResponseTransfer();

        $salesOrderAmendmentTransfer = $this->salesOrderAmendmentRepository->findSalesOrderAmendmentByDeleteCriteria(
            $salesOrderAmendmentDeleteCriteriaTransfer,
        );

        if ($salesOrderAmendmentTransfer === null) {
            return $salesOrderAmendmentResponseTransfer->addError($this->createSalesOrderAmendmentNotFoundError());
        }

        $salesOrderAmendmentTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderAmendmentTransfer) {
            return $this->executeDeleteSalesOrderAmendmentTransaction($salesOrderAmendmentTransfer);
        });

        return $salesOrderAmendmentResponseTransfer->setSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected function executeDeleteSalesOrderAmendmentTransaction(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        $salesOrderAmendmentTransfer = $this->executeSalesOrderAmendmentPreDeletePlugins($salesOrderAmendmentTransfer);
        $this->salesOrderAmendmentEntityManager->deleteSalesOrderAmendment($salesOrderAmendmentTransfer);

        return $this->executeSalesOrderAmendmentPostDeletePlugins($salesOrderAmendmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected function executeSalesOrderAmendmentPreDeletePlugins(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        foreach ($this->salesOrderAmendmentPreDeletePlugins as $salesOrderAmendmentPreDeletePlugin) {
            $salesOrderAmendmentTransfer = $salesOrderAmendmentPreDeletePlugin->preDelete($salesOrderAmendmentTransfer);
        }

        return $salesOrderAmendmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected function executeSalesOrderAmendmentPostDeletePlugins(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        foreach ($this->salesOrderAmendmentPostDeletePlugins as $salesOrderAmendmentPostDeletePlugin) {
            $salesOrderAmendmentTransfer = $salesOrderAmendmentPostDeletePlugin->postDelete($salesOrderAmendmentTransfer);
        }

        return $salesOrderAmendmentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createSalesOrderAmendmentNotFoundError(): ErrorTransfer
    {
        return (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_SALES_ORDER_AMENDMENT_NOT_FOUND);
    }
}
