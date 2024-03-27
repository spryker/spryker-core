<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Communication\Expander;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;

class MerchantRelationRequestAclEntityConfigurationExpander implements MerchantRelationRequestAclEntityConfigurationExpanderInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_UPDATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_UPDATE = 0b100;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_DELETE}
     *
     * @var int
     */
    protected const OPERATION_MASK_DELETE = 0b1000;

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        if (
            $aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest'
            || $aclEntityMetadataConfigTransfer->getModelName() === null
        ) {
            $aclEntityMetadataConfigTransfer = $this->expandForMerchantRelationRequestModel(
                $aclEntityMetadataConfigTransfer,
            );
        }

        if ($aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit') {
            $aclEntityMetadataConfigTransfer = $this->expandForMerchantRelationRequestToCompanyBusinessUnitModel(
                $aclEntityMetadataConfigTransfer,
            );
        }

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function expandForMerchantRelationRequestModel(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ | static::OPERATION_MASK_UPDATE),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyUser\Persistence\SpyCompanyUser',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyUser\Persistence\SpyCompanyUser')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )->addAclEntityMetadata(
                'Orm\Zed\Customer\Persistence\SpyCustomer',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Customer\Persistence\SpyCustomer')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CompanyUser\Persistence\SpyCompanyUser')),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest')),
            )->addAclEntityMetadata(
                'Orm\Zed\Company\Persistence\SpyCompany',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Company\Persistence\SpyCompany')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequest'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function expandForMerchantRelationRequestToCompanyBusinessUnitModel(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_DELETE),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit')),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
