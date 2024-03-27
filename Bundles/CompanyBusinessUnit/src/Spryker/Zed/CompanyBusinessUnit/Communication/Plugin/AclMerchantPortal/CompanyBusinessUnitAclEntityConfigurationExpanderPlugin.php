<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with company business unit composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\Company\Persistence\SpyCompany',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Company\Persistence\SpyCompany')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit'))
                    ->setIsSubEntity(true),
            )->addAclEntityMetadata(
                'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit'),
            );

        if ($aclEntityMetadataConfigTransfer->getModelName() === 'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit') {
            $aclEntityMetadataConfigTransfer
                ->getAclEntityMetadataCollectionOrFail()
                ->addAclEntityMetadata(
                    'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
                    (new AclEntityMetadataTransfer())
                        ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit'),
                );
        }

        return $aclEntityMetadataConfigTransfer;
    }
}
