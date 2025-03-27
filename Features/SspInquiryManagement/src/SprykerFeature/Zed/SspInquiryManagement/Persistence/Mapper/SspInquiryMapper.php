<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry;
use Propel\Runtime\Collection\ObjectCollection;

class SspInquiryMapper implements SspInquiryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry $sspInquiryEntity
     *
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry
     */
    public function mapSspInquiryTransferToSspInquiryEntity(SspInquiryTransfer $sspInquiryTransfer, SpySspInquiry $sspInquiryEntity): SpySspInquiry
    {
         $sspInquiryEntity
            ->fromArray($sspInquiryTransfer->toArray())
            ->setFkCompanyUser($sspInquiryTransfer->getCompanyUser() ? $sspInquiryTransfer->getCompanyUser()->getIdCompanyUser() : null)
            ->setFkStore($sspInquiryTransfer->getStoreOrFail()->getIdStoreOrFail());

        return $sspInquiryEntity;
    }

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry $sspInquiryEntity
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function mapSspInquiryEntityToSspInquiryTransfer(SpySspInquiry $sspInquiryEntity, SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquiryTransfer->fromArray($sspInquiryEntity->toArray(), true);
        if ($sspInquiryEntity->getCreatedAt()) {
             $sspInquiryTransfer->setCreatedDate($sspInquiryEntity->getCreatedAt()->format('Y-m-d H:i:s'));
        }

        $stateMachineItemState = $sspInquiryEntity->getStateMachineItemState();
        if ($stateMachineItemState) {
             $sspInquiryTransfer->setStatus($stateMachineItemState->getName());
        }

        if ($sspInquiryEntity->hasVirtualColumn(SspAssetTransfer::ID_SSP_ASSET)) {
            $sspInquiryTransfer->setSspAsset(
                (new SspAssetTransfer())->setIdSspAsset($sspInquiryEntity->getVirtualColumn(SspAssetTransfer::ID_SSP_ASSET)),
            );
        }

        $sspInquiryTransfer->setStore((new StoreTransfer())->fromArray($sspInquiryEntity->getSpyStore()->toArray(), true));

        $sspInquiryTransfer->setCompanyUser(
            (new CompanyUserTransfer())->setIdCompanyUser($sspInquiryEntity->getFkCompanyUser()),
        );

        return $sspInquiryTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry> $sspInquiryEntities
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

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry $sspInquiryEntity
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
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
}
