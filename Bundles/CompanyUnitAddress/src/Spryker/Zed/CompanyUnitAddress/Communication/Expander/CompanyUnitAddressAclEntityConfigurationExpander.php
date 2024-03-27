<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication\Expander;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;

class CompanyUnitAddressAclEntityConfigurationExpander implements CompanyUnitAddressAclEntityConfigurationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        if (
            $aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress'
            || $aclEntityMetadataConfigTransfer->getModelName() === null
        ) {
            $aclEntityMetadataConfigTransfer = $this->expandForCompanyUnitAddressModelOrNull(
                $aclEntityMetadataConfigTransfer,
            );
        }

        if ($aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress') {
            $aclEntityMetadataConfigTransfer = $this->expandForCompanyUnitAddressModel($aclEntityMetadataConfigTransfer);
        }

        if ($aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnit') {
            $aclEntityMetadataConfigTransfer = $this->expandForCompanyUnitAddressToCompanyBusinessUnitModel(
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
    protected function expandForCompanyUnitAddressModelOrNull(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress'),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnit')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress')),
            );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function expandForCompanyUnitAddressModel(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\Country\Persistence\SpyCountry',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Country\Persistence\SpyCountry')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress')),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnit')),
            );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function expandForCompanyUnitAddressToCompanyBusinessUnitModel(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnit'),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
