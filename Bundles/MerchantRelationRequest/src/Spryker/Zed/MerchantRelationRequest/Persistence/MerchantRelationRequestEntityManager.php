<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence;

use Generated\Shared\Transfer\MerchantRelationRequestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestPersistenceFactory getFactory()
 */
class MerchantRelationRequestEntityManager extends AbstractEntityManager implements MerchantRelationRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function createMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        $merchantRelationRequestMapper = $this->getFactory()->createMerchantRelationRequestMapper();

        $merchantRelationRequestEntity = $merchantRelationRequestMapper
            ->mapMerchantRelationRequestTransferToMerchantRelationRequestEntity(
                $merchantRelationRequestTransfer,
                new SpyMerchantRelationRequest(),
            );

        $merchantRelationRequestEntity->save();

        return $merchantRelationRequestMapper->mapMerchantRelationRequestEntityToMerchantRelationRequestTransfer(
            $merchantRelationRequestEntity,
            $merchantRelationRequestTransfer,
        );
    }

    /**
     * @param int $idMerchantRelationRequest
     * @param list<int> $companyBusinessUnitIds
     *
     * @return void
     */
    public function createAssigneeCompanyBusinessUnits(int $idMerchantRelationRequest, array $companyBusinessUnitIds): void
    {
        foreach ($companyBusinessUnitIds as $idCompanyBusinessUnit) {
            (new SpyMerchantRelationRequestToCompanyBusinessUnit())
                ->setFkMerchantRelationRequest($idMerchantRelationRequest)
                ->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
                ->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function updateMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        $merchantRelationRequestMapper = $this->getFactory()->createMerchantRelationRequestMapper();

        $merchantRelationRequestEntity = $this->getFactory()
            ->getMerchantRelationRequestQuery()
            ->filterByUuid($merchantRelationRequestTransfer->getUuidOrFail())
            ->findOne();

        if ($merchantRelationRequestEntity === null) {
            return $merchantRelationRequestTransfer;
        }

        $merchantRelationRequestEntity = $merchantRelationRequestMapper
            ->mapMerchantRelationRequestTransferToMerchantRelationRequestEntity(
                $merchantRelationRequestTransfer,
                $merchantRelationRequestEntity,
            );

        $merchantRelationRequestEntity->save();

        return $merchantRelationRequestMapper->mapMerchantRelationRequestEntityToMerchantRelationRequestTransfer(
            $merchantRelationRequestEntity,
            $merchantRelationRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestDeleteCriteriaTransfer $merchantRelationRequestDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationRequestCollection(
        MerchantRelationRequestDeleteCriteriaTransfer $merchantRelationRequestDeleteCriteriaTransfer
    ): void {
        $merchantRelationRequestQuery = $this->getFactory()->getMerchantRelationRequestQuery();

        if ($merchantRelationRequestDeleteCriteriaTransfer->getMerchantRelationRequestIds()) {
            $merchantRelationRequestQuery->filterByIdMerchantRelationRequest_In(
                $merchantRelationRequestDeleteCriteriaTransfer->getMerchantRelationRequestIds(),
            );
        }

        if ($merchantRelationRequestDeleteCriteriaTransfer->getOwnerCompanyBusinessUnitIds()) {
            $merchantRelationRequestQuery->filterByFkCompanyBusinessUnit_In(
                $merchantRelationRequestDeleteCriteriaTransfer->getOwnerCompanyBusinessUnitIds(),
            );
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $merchantRelationRequestCollection */
        $merchantRelationRequestCollection = $merchantRelationRequestQuery->find();
        $merchantRelationRequestCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationRequestToCompanyBusinessUnitCollection(
        MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer
    ): void {
        $merchantRelationRequestToCompanyBusinessUnitQuery = $this->getFactory()
            ->getMerchantRelationRequestToCompanyBusinessUnitQuery();

        if ($merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer->getMerchantRelationRequestIds()) {
            $merchantRelationRequestToCompanyBusinessUnitQuery->filterByFkMerchantRelationRequest_In(
                $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer->getMerchantRelationRequestIds(),
            );
        }

        if ($merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer->getCompanyBusinessUnitIds()) {
            $merchantRelationRequestToCompanyBusinessUnitQuery->filterByFkCompanyBusinessUnit_In(
                $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer->getCompanyBusinessUnitIds(),
            );
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $merchantRelationRequestToCompanyBusinessCollection */
        $merchantRelationRequestToCompanyBusinessCollection = $merchantRelationRequestToCompanyBusinessUnitQuery->find();
        $merchantRelationRequestToCompanyBusinessCollection->delete();
    }
}
