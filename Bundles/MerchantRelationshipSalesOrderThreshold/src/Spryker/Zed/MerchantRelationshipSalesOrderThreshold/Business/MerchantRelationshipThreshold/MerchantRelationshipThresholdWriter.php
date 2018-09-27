<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepository;

class MerchantRelationshipThresholdWriter implements MerchantRelationshipThresholdWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface
     */
    protected $merchantRelationshipSalesOrderThresholdEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepository
     */
    protected $merchantRelationshipSalesOrderThresholdRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface
     */
    protected $glossaryKeyGenerator;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface
     */
    protected $translationWriter;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface $merchantRelationshipSalesOrderThresholdEntityManager
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepository $merchantRelationshipSalesOrderThresholdRepository
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface $glossaryKeyGenerator
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface $translationWriter
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade,
        MerchantRelationshipSalesOrderThresholdEntityManagerInterface $merchantRelationshipSalesOrderThresholdEntityManager,
        MerchantRelationshipSalesOrderThresholdRepository $merchantRelationshipSalesOrderThresholdRepository,
        MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface $glossaryKeyGenerator,
        MerchantRelationshipSalesOrderThresholdTranslationWriterInterface $translationWriter
    ) {
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
        $this->merchantRelationshipSalesOrderThresholdEntityManager = $merchantRelationshipSalesOrderThresholdEntityManager;
        $this->merchantRelationshipSalesOrderThresholdRepository = $merchantRelationshipSalesOrderThresholdRepository;
        $this->glossaryKeyGenerator = $glossaryKeyGenerator;
        $this->translationWriter = $translationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function saveMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $this->hydrateSalesOrderThresholdType($merchantRelationshipSalesOrderThresholdTransfer);

        if (!$merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey()) {
            $this->glossaryKeyGenerator->assignMessageGlossaryKey($merchantRelationshipSalesOrderThresholdTransfer);
        }
        $this->merchantRelationshipSalesOrderThresholdEntityManager
            ->saveMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);

        $this->translationWriter->saveLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer);

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return bool
     */
    public function deleteMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): bool {
        $merchantRelationshipSalesOrderThresholdTransfer->requireIdMerchantRelationshipSalesOrderThreshold();
        $merchantRelationshipSalesOrderThresholdTransfer = $this->merchantRelationshipSalesOrderThresholdRepository
            ->findMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);

        if (!$merchantRelationshipSalesOrderThresholdTransfer) {
            return false;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipSalesOrderThresholdTransfer) {
            $this->executeDeleteMerchantRelationshipSalesOrderThresholdTransaction($merchantRelationshipSalesOrderThresholdTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return bool
     */
    protected function executeDeleteMerchantRelationshipSalesOrderThresholdTransaction(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): bool {
        $idDeleted = $this->merchantRelationshipSalesOrderThresholdEntityManager
            ->deleteMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);

        if ($idDeleted) {
            $this->translationWriter->deleteLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer);
        }

        return $idDeleted;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function hydrateSalesOrderThresholdType(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $salesOrderThresholdTypeTransfer = $this->salesOrderThresholdFacade
            ->getSalesOrderThresholdTypeByKey(
                $merchantRelationshipSalesOrderThresholdTransfer
                    ->getSalesOrderThresholdValue()
                    ->getSalesOrderThresholdType()
            );

        $merchantRelationshipSalesOrderThresholdTransfer
            ->getSalesOrderThresholdValue()
            ->setSalesOrderThresholdType($salesOrderThresholdTypeTransfer);

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
