<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdEntityManagerInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface;

class SalesOrderThresholdWriter implements SalesOrderThresholdWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    protected $salesOrderThresholdStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdEntityManagerInterface
     */
    protected $salesOrderThresholdEntityManager;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface
     */
    protected $salesOrderThresholdRepository;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface
     */
    protected $glossaryKeyGenerator;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface
     */
    protected $translationWriter;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdEntityManagerInterface $salesOrderThresholdEntityManager
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface $glossaryKeyGenerator
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface $translationWriter
     */
    public function __construct(
        SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver,
        SalesOrderThresholdEntityManagerInterface $salesOrderThresholdEntityManager,
        SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository,
        SalesOrderThresholdGlossaryKeyGeneratorInterface $glossaryKeyGenerator,
        SalesOrderThresholdTranslationWriterInterface $translationWriter
    ) {
        $this->salesOrderThresholdStrategyResolver = $salesOrderThresholdStrategyResolver;
        $this->salesOrderThresholdEntityManager = $salesOrderThresholdEntityManager;
        $this->salesOrderThresholdRepository = $salesOrderThresholdRepository;
        $this->glossaryKeyGenerator = $glossaryKeyGenerator;
        $this->translationWriter = $translationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer {
        $salesOrderThresholdTransfer->requireSalesOrderThresholdValue();

        $salesOrderThresholdTransfer
            ->getSalesOrderThresholdValue()
            ->getSalesOrderThresholdType()
            ->requireKey();

        $salesOrderThresholdStrategy = $this->salesOrderThresholdStrategyResolver
            ->resolveSalesOrderThresholdStrategy(
                $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey()
            );

        if (!$salesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->getSalesOrderThresholdType()
            ->getIdSalesOrderThresholdType()
        ) {
            $salesOrderThresholdTypeTransfer = $this->salesOrderThresholdEntityManager
                ->saveSalesOrderThresholdType($salesOrderThresholdStrategy->toTransfer());

            $salesOrderThresholdTransfer->getSalesOrderThresholdValue()
                ->setSalesOrderThresholdType(
                    $salesOrderThresholdTypeTransfer
                );
        }

        if (!$salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey()) {
            $this->glossaryKeyGenerator->assignMessageGlossaryKey($salesOrderThresholdTransfer);
        }
        $this->salesOrderThresholdEntityManager->saveSalesOrderThreshold($salesOrderThresholdTransfer);

        $this->translationWriter->saveLocalizedMessages($salesOrderThresholdTransfer);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return bool
     */
    public function deleteSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): bool {
        $salesOrderThresholdTransfer->requireIdSalesOrderThreshold();
        $salesOrderThresholdTransfer = $this->salesOrderThresholdRepository
            ->findSalesOrderThreshold($salesOrderThresholdTransfer);

        if (!$salesOrderThresholdTransfer) {
            return false;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderThresholdTransfer) {
            return $this->executeDeleteSalesOrderThresholdTransaction($salesOrderThresholdTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function saveSalesOrderThresholdType(SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer): SalesOrderThresholdTypeTransfer
    {
        return $this->salesOrderThresholdEntityManager->saveSalesOrderThresholdType($salesOrderThresholdTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return bool
     */
    protected function executeDeleteSalesOrderThresholdTransaction(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): bool {
        $idDeleted = $this->salesOrderThresholdEntityManager
            ->deleteSalesOrderThreshold($salesOrderThresholdTransfer);

        if ($idDeleted) {
            $this->translationWriter->deleteLocalizedMessages($salesOrderThresholdTransfer);
        }

        return $idDeleted;
    }
}
