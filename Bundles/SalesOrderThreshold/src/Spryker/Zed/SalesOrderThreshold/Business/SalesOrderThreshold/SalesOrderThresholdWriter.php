<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdEntityManagerInterface;

class SalesOrderThresholdWriter implements SalesOrderThresholdWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    protected $salesOrderThresholdStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdEntityManagerInterface
     */
    protected $salesOrderThresholdEntityManager;

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
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface $glossaryKeyGenerator
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface $translationWriter
     */
    public function __construct(
        SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver,
        SalesOrderThresholdEntityManagerInterface $salesOrderThresholdEntityManager,
        SalesOrderThresholdGlossaryKeyGeneratorInterface $glossaryKeyGenerator,
        SalesOrderThresholdTranslationWriterInterface $translationWriter
    ) {
        $this->salesOrderThresholdStrategyResolver = $salesOrderThresholdStrategyResolver;
        $this->salesOrderThresholdEntityManager = $salesOrderThresholdEntityManager;
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

        $this->glossaryKeyGenerator->assignMessageGlossaryKey($salesOrderThresholdTransfer);
        $this->salesOrderThresholdEntityManager->saveSalesOrderThreshold($salesOrderThresholdTransfer);

        $this->translationWriter->saveLocalizedMessages($salesOrderThresholdTransfer);

        return $salesOrderThresholdTransfer;
    }
}
