<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 */
class ProductAttributeAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with product attribute composite data.
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
                'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Product\Persistence\SpyProductAttributeKey')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
