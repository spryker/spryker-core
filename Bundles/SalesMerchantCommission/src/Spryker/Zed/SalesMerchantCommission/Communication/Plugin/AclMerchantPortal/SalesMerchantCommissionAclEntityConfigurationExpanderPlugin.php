<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantCommission\Communication\SalesMerchantCommissionCommunicationFactory getFactory()
 */
class SalesMerchantCommissionAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with sales merchant commission composite data.
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
                'Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
