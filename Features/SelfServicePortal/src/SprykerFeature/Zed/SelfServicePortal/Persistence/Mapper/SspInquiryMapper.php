<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineItemStateTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class SspInquiryMapper implements SspInquiryMapperInterface
{
    public function mapSspInquiryTransferToSspInquiryEntity(SspInquiryTransfer $sspInquiryTransfer, SpySspInquiry $sspInquiryEntity): SpySspInquiry
    {
         $sspInquiryEntity
            ->fromArray($sspInquiryTransfer->toArray())
            ->setFkCompanyUser($sspInquiryTransfer->getCompanyUser() ? $sspInquiryTransfer->getCompanyUser()->getIdCompanyUser() : null);

        return $sspInquiryEntity;
    }

    public function mapSspInquiryEntityToSspInquiryTransfer(SpySspInquiry $sspInquiryEntity, SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquiryTransfer->fromArray($sspInquiryEntity->toArray(), true);
        if ($sspInquiryEntity->getCreatedAt()) {
            $sspInquiryTransfer->setCreatedDate($sspInquiryEntity->getCreatedAt()->format('Y-m-d H:i:s'));
        }

        $stateMachineItemState = $sspInquiryEntity->getStateMachineItemState();
        if ($stateMachineItemState) {
            $sspInquiryTransfer->setStatus($stateMachineItemState->getName());
            $sspInquiryTransfer->setStateMachineItemState(
                (new StateMachineItemStateTransfer())
                    ->fromArray($stateMachineItemState->toArray(), true),
            );
        }

        if ($sspInquiryEntity->hasVirtualColumn(SspAssetTransfer::ID_SSP_ASSET)) {
            $sspInquiryTransfer->setSspAsset(
                (new SspAssetTransfer())->setIdSspAsset($sspInquiryEntity->getVirtualColumn(SspAssetTransfer::ID_SSP_ASSET)),
            );
        }

        $sspInquiryTransfer->setCompanyUser(
            (new CompanyUserTransfer())->setIdCompanyUser($sspInquiryEntity->getFkCompanyUser()),
        );

        return $sspInquiryTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry> $sspInquiryEntities
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function mapSspInquiryEntityCollectionToStateMachineItemTransfers(
        ObjectCollection $sspInquiryEntities
    ): array {
        $stateMachineItemTransfers = [];
        foreach ($sspInquiryEntities as $sspInquiryEntity) {
            $stateMachineItemTransfer = $this->mapSspInquiryEntityToStateMachineItemTransfer(
                $sspInquiryEntity,
                new StateMachineItemTransfer(),
            );

            $stateMachineItemTransfers[] = $stateMachineItemTransfer;
        }

        return $stateMachineItemTransfers;
    }

    protected function mapSspInquiryEntityToStateMachineItemTransfer(
        SpySspInquiry $sspInquiryEntity,
        StateMachineItemTransfer $stateMachineItemTransfer
    ): StateMachineItemTransfer {
        /** @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState $stateMachineItemStateEntity */
        $stateMachineItemStateEntity = $sspInquiryEntity->getStateMachineItemState();

        $stateMachineItemTransfer = $stateMachineItemTransfer->fromArray($sspInquiryEntity->toArray(), true)
            ->setIdentifier($sspInquiryEntity->getIdSspInquiry())
            ->setIdItemState($sspInquiryEntity->getFkStateMachineItemState())
            ->setStateName($stateMachineItemStateEntity->getName())
            ->setProcessName($stateMachineItemStateEntity->getProcess()->getName())
            ->setStateMachineName($stateMachineItemStateEntity->getProcess()->getStateMachineName());

        return $stateMachineItemTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFile> $sspInquiryFileEntities
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function mapSspInquiryFileEntitiesToSspInquiryCollectionTransfer(
        Collection $sspInquiryFileEntities,
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
    ): SspInquiryCollectionTransfer {
        $sspInquiryFiles = [];

        foreach ($sspInquiryFileEntities as $sspInquiryFileEntity) {
            $sspInquiryFiles[$sspInquiryFileEntity->getFkSspInquiry()][] = (new FileTransfer())
                ->setIdFile($sspInquiryFileEntity->getFkFile())
                ->setUuid($sspInquiryFileEntity->getUuid());
        }

        foreach ($sspInquiryFiles as $idSspInquiry => $files) {
            $sspInquiryCollectionTransfer->addSspInquiry(
                (new SspInquiryTransfer())
                    ->setIdSspInquiry((int)$idSspInquiry)
                    ->setFiles(new ArrayObject($files)),
            );
        }

        return $sspInquiryCollectionTransfer;
    }
}
