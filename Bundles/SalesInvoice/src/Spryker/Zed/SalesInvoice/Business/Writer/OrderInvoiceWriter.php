<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\Writer;

use Generated\Shared\Transfer\OrderInvoiceResponseTransfer;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesInvoice\Business\ReferenceGenerator\OrderInvoiceReferenceGeneratorInterface;
use Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface;
use Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface;
use Spryker\Zed\SalesInvoice\SalesInvoiceConfig;

class OrderInvoiceWriter implements OrderInvoiceWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesInvoice\Business\ReferenceGenerator\OrderInvoiceReferenceGeneratorInterface
     */
    protected $orderInvoiceReferenceGenerator;

    /**
     * @var \Spryker\Zed\SalesInvoice\SalesInvoiceConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoiceBeforeSavePluginInterface[]
     */
    protected $orderInvoiceBeforeSavePlugins;

    /**
     * @param \Spryker\Zed\SalesInvoice\SalesInvoiceConfig $config
     * @param \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface $repository
     * @param \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface $entityManager
     * @param \Spryker\Zed\SalesInvoice\Business\ReferenceGenerator\OrderInvoiceReferenceGeneratorInterface $orderInvoiceReferenceGenerator
     * @param \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoiceBeforeSavePluginInterface[] $orderInvoiceBeforeSavePlugins
     */
    public function __construct(
        SalesInvoiceConfig $config,
        SalesInvoiceRepositoryInterface $repository,
        SalesInvoiceEntityManagerInterface $entityManager,
        OrderInvoiceReferenceGeneratorInterface $orderInvoiceReferenceGenerator,
        array $orderInvoiceBeforeSavePlugins
    ) {
        $this->config = $config;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->orderInvoiceReferenceGenerator = $orderInvoiceReferenceGenerator;
        $this->orderInvoiceBeforeSavePlugins = $orderInvoiceBeforeSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceResponseTransfer
     */
    public function generateOrderInvoice(OrderTransfer $orderTransfer): OrderInvoiceResponseTransfer
    {
        $orderTransfer->requireIdSalesOrder();

        if ($this->repository->checkOrderInvoiceExistenceByOrderId($orderTransfer->getIdSalesOrder())) {
            return (new OrderInvoiceResponseTransfer())
                ->setIsSuccessful(false);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($orderTransfer) {
            return $this->executeGenerateOrderInvoiceTransaction($orderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceResponseTransfer
     */
    protected function executeGenerateOrderInvoiceTransaction(OrderTransfer $orderTransfer): OrderInvoiceResponseTransfer
    {
        $orderInvoiceTransfer = (new OrderInvoiceTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setTemplatePath($this->config->getOrderInvoiceTemplatePath())
            ->setIssueDate(date('Y-m-d H:i:s'))
            ->setReference($this->orderInvoiceReferenceGenerator->generateOrderInvoiceReference());

        $orderInvoiceTransfer = $this->executeOrderInvoiceBeforeSavePlugins($orderInvoiceTransfer, $orderTransfer);

        $orderInvoiceTransfer = $this->entityManager->createOrderInvoice($orderInvoiceTransfer);

        return (new OrderInvoiceResponseTransfer())
            ->setOrderInvoice($orderInvoiceTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    protected function executeOrderInvoiceBeforeSavePlugins(
        OrderInvoiceTransfer $orderInvoiceTransfer,
        OrderTransfer $orderTransfer
    ): OrderInvoiceTransfer {
        foreach ($this->orderInvoiceBeforeSavePlugins as $orderInvoiceBeforeSavePlugin) {
            $orderInvoiceTransfer = $orderInvoiceBeforeSavePlugin->execute($orderInvoiceTransfer, $orderTransfer);
        }

        return $orderInvoiceTransfer;
    }
}
