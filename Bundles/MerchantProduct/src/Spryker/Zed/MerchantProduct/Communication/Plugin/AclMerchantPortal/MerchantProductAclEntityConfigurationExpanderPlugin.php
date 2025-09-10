<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 * @method \Spryker\Zed\MerchantProduct\Communication\MerchantProductCommunicationFactory getFactory()
 */
class MerchantProductAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant product composite data.
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
                'Orm\Zed\Product\Persistence\SpyProductAbstract',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract'))
                    ->setIsSubEntity(false),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
