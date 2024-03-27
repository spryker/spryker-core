<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication\Expander;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;

class MerchantRelationshipAclEntityConfigurationExpander implements MerchantRelationshipAclEntityConfigurationExpanderInterface
{
    /**
     * @uses \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * @uses \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_CREATE
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
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'))
                    ->setIsSubEntity(true),
            );

        if ($aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship') {
            $aclEntityMetadataConfigTransfer = $this->expandForMerchantRelationshipModel(
                $aclEntityMetadataConfigTransfer,
            );
        }

        if ($aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit') {
            $aclEntityMetadataConfigTransfer = $this->expandForMerchantRelationshipToCompanyBusinessUnitModel(
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
    protected function expandForMerchantRelationshipModel(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship'))
                    ->setIsSubEntity(true),
            )->addAclEntityMetadata(
                'Orm\Zed\Company\Persistence\SpyCompany',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Company\Persistence\SpyCompany')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function expandForMerchantRelationshipToCompanyBusinessUnitModel(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit'),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit')),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
