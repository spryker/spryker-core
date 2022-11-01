<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface;

class MerchantRelationshipSalesOrderThresholdDeleter implements MerchantRelationshipSalesOrderThresholdDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface
     */
    protected MerchantRelationshipSalesOrderThresholdEntityManagerInterface $merchantRelationshipSalesOrderThresholdEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface
     */
    protected MerchantRelationshipSalesOrderThresholdRepositoryInterface $merchantRelationshipSalesOrderThresholdRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface
     */
    protected MerchantRelationshipSalesOrderThresholdTranslationWriterInterface $merchantRelationshipSalesOrderThresholdTranslationWriter;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipSalesOrderThresholdMapperInterface
     */
    protected MerchantRelationshipSalesOrderThresholdMapperInterface $merchantRelationshipSalesOrderThresholdMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface $merchantRelationshipSalesOrderThresholdEntityManager
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface $merchantRelationshipSalesOrderThresholdRepository
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface $merchantRelationshipSalesOrderThresholdTranslationWriter
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipSalesOrderThresholdMapperInterface $merchantRelationshipSalesOrderThresholdMapper
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdEntityManagerInterface $merchantRelationshipSalesOrderThresholdEntityManager,
        MerchantRelationshipSalesOrderThresholdRepositoryInterface $merchantRelationshipSalesOrderThresholdRepository,
        MerchantRelationshipSalesOrderThresholdTranslationWriterInterface $merchantRelationshipSalesOrderThresholdTranslationWriter,
        MerchantRelationshipSalesOrderThresholdMapperInterface $merchantRelationshipSalesOrderThresholdMapper
    ) {
        $this->merchantRelationshipSalesOrderThresholdEntityManager = $merchantRelationshipSalesOrderThresholdEntityManager;
        $this->merchantRelationshipSalesOrderThresholdRepository = $merchantRelationshipSalesOrderThresholdRepository;
        $this->merchantRelationshipSalesOrderThresholdTranslationWriter = $merchantRelationshipSalesOrderThresholdTranslationWriter;
        $this->merchantRelationshipSalesOrderThresholdMapper = $merchantRelationshipSalesOrderThresholdMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    public function deleteMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
        $merchantRelationshipSalesOrderThresholdCollectionTransfer = $this->getMerchantRelationshipSalesOrderThresholdCollection(
            $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer,
        );

        $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer = $this->merchantRelationshipSalesOrderThresholdMapper
            ->mapThresholdCollectionToThresholdCollectionResponse(
                $merchantRelationshipSalesOrderThresholdCollectionTransfer,
                new MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer(),
            );

        if ($merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer->getIsTransactional() === false) {
            return $this->nonTransactionalDeleteMerchantRelationshipSalesOrderThresholdCollection(
                $merchantRelationshipSalesOrderThresholdCollectionTransfer,
                $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer,
            );
        }

        return $this->transactionalDeleteMerchantRelationshipSalesOrderThresholdCollection(
            $merchantRelationshipSalesOrderThresholdCollectionTransfer,
            $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer
     */
    protected function getMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionTransfer {
        $merchantRelationshipSalesOrderThresholdCriteriaTransfer = $this->merchantRelationshipSalesOrderThresholdMapper
            ->mapThresholdCollectionDeleteCriteriaToThresholdCriteria(
                $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer,
                new MerchantRelationshipSalesOrderThresholdCriteriaTransfer(),
            );

        return $this->merchantRelationshipSalesOrderThresholdRepository->getMerchantRelationshipSalesOrderThresholdCollection(
            $merchantRelationshipSalesOrderThresholdCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer $merchantRelationshipSalesOrderThresholdCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    protected function nonTransactionalDeleteMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCollectionTransfer $merchantRelationshipSalesOrderThresholdCollectionTransfer,
        MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
        foreach ($merchantRelationshipSalesOrderThresholdCollectionTransfer->getMerchantRelationshipSalesOrderThresholds() as $merchantRelationshipSalesOrderThresholdTransfer) {
            $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipSalesOrderThresholdTransfer, $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
                return $this->executeDeleteMerchantRelationshipSalesOrderThresholdTransaction(
                    $merchantRelationshipSalesOrderThresholdTransfer,
                    $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer,
                );
            });
        }

        return $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer $merchantRelationshipSalesOrderThresholdCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    protected function transactionalDeleteMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCollectionTransfer $merchantRelationshipSalesOrderThresholdCollectionTransfer,
        MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipSalesOrderThresholdCollectionTransfer, $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
            foreach ($merchantRelationshipSalesOrderThresholdCollectionTransfer->getMerchantRelationshipSalesOrderThresholds() as $merchantRelationshipSalesOrderThresholdTransfer) {
                $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer = $this->executeDeleteMerchantRelationshipSalesOrderThresholdTransaction(
                    $merchantRelationshipSalesOrderThresholdTransfer,
                    $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer,
                );

                if (count($merchantRelationshipSalesOrderThresholdCollectionResponseTransfer->getErrors()) > 0) {
                    return $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
                }
            }

            return $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    protected function executeDeleteMerchantRelationshipSalesOrderThresholdTransaction(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer,
        MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer {
        $idDeleted = $this->merchantRelationshipSalesOrderThresholdEntityManager
            ->deleteMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);

        if ($idDeleted === false) {
            $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer->addError(
                $this->createMerchantRelationshipSalesOrderThresholdErrorMessage($merchantRelationshipSalesOrderThresholdTransfer),
            );

            return $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
        }

        $this->merchantRelationshipSalesOrderThresholdTranslationWriter
            ->deleteLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer);

        return $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createMerchantRelationshipSalesOrderThresholdErrorMessage(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): ErrorTransfer {
        $errorTransfer = (new ErrorTransfer())->setMessage('Merchant Relationship Sales Order Threshold could not be deleted.');

        $idMerchantRelationshipSalesOrderThreshold = $merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold();
        if ($idMerchantRelationshipSalesOrderThreshold !== null) {
            $errorTransfer->setEntityIdentifier((string)$idMerchantRelationshipSalesOrderThreshold);
        }

        return $errorTransfer;
    }
}
