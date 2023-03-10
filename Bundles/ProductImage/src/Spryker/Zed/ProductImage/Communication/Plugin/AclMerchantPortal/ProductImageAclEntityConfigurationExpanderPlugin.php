<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 */
class ProductImageAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with product image composite data.
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
                'Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImageSet'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\ProductImage\Persistence\SpyProductImage',
                (new AclEntityMetadataTransfer())->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImage'),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\ProductImage\Persistence\SpyProductImageSet',
                (new AclEntityMetadataTransfer())->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImageSet'),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
