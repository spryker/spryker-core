<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantRelationRequestMapper
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\MerchantMapper
     */
    protected MerchantMapper $merchantMapper;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyUserMapper
     */
    protected CompanyUserMapper $companyUserMapper;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyBusinessUnitMapper
     */
    protected CompanyBusinessUnitMapper $companyBusinessUnitMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\MerchantMapper $merchantMapper
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyUserMapper $companyUserMapper
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyBusinessUnitMapper $companyBusinessUnitMapper
     */
    public function __construct(
        MerchantMapper $merchantMapper,
        CompanyUserMapper $companyUserMapper,
        CompanyBusinessUnitMapper $companyBusinessUnitMapper
    ) {
        $this->merchantMapper = $merchantMapper;
        $this->companyUserMapper = $companyUserMapper;
        $this->companyBusinessUnitMapper = $companyBusinessUnitMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest> $merchantRelationRequestEntities
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function mapMerchantRelationRequestEntitiesToMerchantRelationRequestCollectionTransfer(
        ObjectCollection $merchantRelationRequestEntities,
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        foreach ($merchantRelationRequestEntities as $merchantRelationRequestEntity) {
            $merchantRelationRequestTransfer = $this->mapMerchantRelationRequestEntityToMerchantRelationRequestTransfer(
                $merchantRelationRequestEntity,
                new MerchantRelationRequestTransfer(),
            );

            $merchantRelationRequestCollectionTransfer->addMerchantRelationRequest($merchantRelationRequestTransfer);
        }

        return $merchantRelationRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest $merchantRelationRequestEntity
     *
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest
     */
    public function mapMerchantRelationRequestTransferToMerchantRelationRequestEntity(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        SpyMerchantRelationRequest $merchantRelationRequestEntity
    ): SpyMerchantRelationRequest {
        return $merchantRelationRequestEntity
            ->fromArray($merchantRelationRequestTransfer->modifiedToArray())
            ->setFkMerchant($merchantRelationRequestTransfer->getMerchantOrFail()->getIdMerchantOrFail())
            ->setFkCompanyUser($merchantRelationRequestTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail())
            ->setFkCompanyBusinessUnit(
                $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            );
    }

    /**
     * @param \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest $merchantRelationRequestEntity
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function mapMerchantRelationRequestEntityToMerchantRelationRequestTransfer(
        SpyMerchantRelationRequest $merchantRelationRequestEntity,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        $merchantRelationRequestTransfer = $merchantRelationRequestTransfer->fromArray(
            $merchantRelationRequestEntity->toArray(),
            true,
        );

        $merchantTransfer = $this->merchantMapper->mapMerchantEntityToMerchantTransfer(
            $merchantRelationRequestEntity->getMerchant(),
            new MerchantTransfer(),
        );
        $merchantRelationRequestTransfer->setMerchant($merchantTransfer);

        $companyUserTransfer = $this->companyUserMapper->mapCompanyUserEntityToCompanyUserTransfer(
            $merchantRelationRequestEntity->getCompanyUser(),
            new CompanyUserTransfer(),
        );
        $merchantRelationRequestTransfer->setCompanyUser($companyUserTransfer);

        $companyBusinessUnitTransfer = $this->companyBusinessUnitMapper->mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
            $merchantRelationRequestEntity->getCompanyBusinessUnit(),
            new CompanyBusinessUnitTransfer(),
        );
        $merchantRelationRequestTransfer->setOwnerCompanyBusinessUnit($companyBusinessUnitTransfer);

        return $merchantRelationRequestTransfer;
    }
}
