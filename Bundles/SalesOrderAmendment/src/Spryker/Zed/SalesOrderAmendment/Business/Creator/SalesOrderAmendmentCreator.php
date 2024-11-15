<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Creator;

use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapperInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface;

class SalesOrderAmendmentCreator implements SalesOrderAmendmentCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface
     */
    protected SalesOrderAmendmentValidatorInterface $salesOrderAmendmentValidator;

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface
     */
    protected SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager;

    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapperInterface
     */
    protected SalesOrderAmendmentMapperInterface $salesOrderAmendmentMapper;

    /**
     * @var list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface>
     */
    protected array $salesOrderAmendmentPreCreatePlugins;

    /**
     * @var list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface>
     */
    protected array $salesOrderAmendmentPostCreatePlugins;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface $salesOrderAmendmentValidator
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapperInterface $salesOrderAmendmentMapper
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface> $salesOrderAmendmentPreCreatePlugins
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface> $salesOrderAmendmentPostCreatePlugins
     */
    public function __construct(
        SalesOrderAmendmentValidatorInterface $salesOrderAmendmentValidator,
        SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager,
        SalesOrderAmendmentMapperInterface $salesOrderAmendmentMapper,
        array $salesOrderAmendmentPreCreatePlugins,
        array $salesOrderAmendmentPostCreatePlugins
    ) {
        $this->salesOrderAmendmentValidator = $salesOrderAmendmentValidator;
        $this->salesOrderAmendmentEntityManager = $salesOrderAmendmentEntityManager;
        $this->salesOrderAmendmentMapper = $salesOrderAmendmentMapper;
        $this->salesOrderAmendmentPreCreatePlugins = $salesOrderAmendmentPreCreatePlugins;
        $this->salesOrderAmendmentPostCreatePlugins = $salesOrderAmendmentPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    public function createSalesOrderAmendment(
        SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer
    ): SalesOrderAmendmentResponseTransfer {
        $this->assertRequiredFields($salesOrderAmendmentRequestTransfer);

        $salesOrderAmendmentTransfer = $this->salesOrderAmendmentMapper
            ->mapSalesOrderAmendmentRequestTransferToSalesOrderAmendmentTransfer(
                $salesOrderAmendmentRequestTransfer,
                new SalesOrderAmendmentTransfer(),
            );

        $salesOrderAmendmentResponseTransfer = $this->salesOrderAmendmentValidator->validate($salesOrderAmendmentTransfer);
        if ($salesOrderAmendmentResponseTransfer->getErrors()->count() > 0) {
            return $salesOrderAmendmentResponseTransfer;
        }

        $salesOrderAmendmentTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderAmendmentTransfer) {
            return $this->executeCreateSalesOrderAmendmentTransaction($salesOrderAmendmentTransfer);
        });

        return $salesOrderAmendmentResponseTransfer->setSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected function executeCreateSalesOrderAmendmentTransaction(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        $salesOrderAmendmentTransfer = $this->executeSalesOrderAmendmentPreCreatePlugins($salesOrderAmendmentTransfer);
        $salesOrderAmendmentTransfer = $this->salesOrderAmendmentEntityManager->createSalesOrderAmendment(
            $salesOrderAmendmentTransfer,
        );

        return $this->executeSalesOrderAmendmentPostCreatePlugins($salesOrderAmendmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected function executeSalesOrderAmendmentPreCreatePlugins(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        foreach ($this->salesOrderAmendmentPreCreatePlugins as $salesOrderAmendmentPreCreatePlugin) {
            $salesOrderAmendmentTransfer = $salesOrderAmendmentPreCreatePlugin->preCreate($salesOrderAmendmentTransfer);
        }

        return $salesOrderAmendmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected function executeSalesOrderAmendmentPostCreatePlugins(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        foreach ($this->salesOrderAmendmentPostCreatePlugins as $salesOrderAmendmentPostCreatePlugin) {
            $salesOrderAmendmentTransfer = $salesOrderAmendmentPostCreatePlugin->postCreate($salesOrderAmendmentTransfer);
        }

        return $salesOrderAmendmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer): void
    {
        $salesOrderAmendmentRequestTransfer
            ->requireAmendmentOrderReference()
            ->requireAmendedOrderReference();
    }
}
