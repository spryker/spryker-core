<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 */
class ProductAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with product composite data.
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
                'Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Product\Persistence\SpyProduct'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Product\Persistence\SpyProduct',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Product\Persistence\SpyProductAbstractStore',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstractStore')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Product\Persistence\SpyProductAttributeKey',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAttributeKey')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
