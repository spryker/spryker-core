<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Deleter;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspModelCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;
use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspModelDeleter implements SspModelDeleterInterface
{
    use TransactionTrait;

    public function __construct(
        protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager,
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository
    ) {
    }

    public function deleteSspModelCollection(
        SspModelCollectionDeleteCriteriaTransfer $sspModelCollectionDeleteCriteriaTransfer
    ): SspModelCollectionResponseTransfer {
        $sspModelCollectionResponseTransfer = $this->validateSspModelCollectionDeleteCriteria($sspModelCollectionDeleteCriteriaTransfer);
        if ($sspModelCollectionResponseTransfer->getErrors()->count()) {
            return $sspModelCollectionResponseTransfer;
        }

        $sspModelCriteriaTransfer = $this->mapSspModelCollectionDeleteCriteriaToSspModelCriteria(
            $sspModelCollectionDeleteCriteriaTransfer,
            new SspModelCriteriaTransfer(),
        );

        $sspModelCollectionTransfer = $this->selfServicePortalRepository->getSspModelCollection($sspModelCriteriaTransfer);

        if ($sspModelCollectionDeleteCriteriaTransfer->getIsTransactional()) {
            return $this->getTransactionHandler()->handleTransaction(function () use ($sspModelCollectionTransfer) {
                return $this->executeDeleteSspModelCollectionTransaction($sspModelCollectionTransfer);
            });
        }

        return $this->executeDeleteSspModelCollectionTransaction($sspModelCollectionTransfer);
    }

    protected function executeDeleteSspModelCollectionTransaction(
        SspModelCollectionTransfer $sspModelCollectionTransfer
    ): SspModelCollectionResponseTransfer {
        $this->selfServicePortalEntityManager->deleteSspModels($sspModelCollectionTransfer);

        return (new SspModelCollectionResponseTransfer())
            ->setSspModels($sspModelCollectionTransfer->getSspModels());
    }

    protected function mapSspModelCollectionDeleteCriteriaToSspModelCriteria(
        SspModelCollectionDeleteCriteriaTransfer $sspModelCollectionDeleteCriteriaTransfer,
        SspModelCriteriaTransfer $sspModelCriteriaTransfer
    ): SspModelCriteriaTransfer {
        $sspModelConditionsTransfer = new SspModelConditionsTransfer();

        if ($sspModelCollectionDeleteCriteriaTransfer->getSspModelIds()) {
            $sspModelConditionsTransfer->setSspModelIds($sspModelCollectionDeleteCriteriaTransfer->getSspModelIds());
        }

        return $sspModelCriteriaTransfer->setSspModelConditions($sspModelConditionsTransfer);
    }

    protected function validateSspModelCollectionDeleteCriteria(
        SspModelCollectionDeleteCriteriaTransfer $sspModelCollectionDeleteCriteriaTransfer
    ): SspModelCollectionResponseTransfer {
        $sspModelCollectionResponseTransfer = new SspModelCollectionResponseTransfer();

        if (!$sspModelCollectionDeleteCriteriaTransfer->getSspModelIds()) {
            $errorTransfer = (new ErrorTransfer())
                ->setMessage('Unconditional deletion is not allowed!');

            $sspModelCollectionResponseTransfer->addError($errorTransfer);
        }

        return $sspModelCollectionResponseTransfer;
    }
}
