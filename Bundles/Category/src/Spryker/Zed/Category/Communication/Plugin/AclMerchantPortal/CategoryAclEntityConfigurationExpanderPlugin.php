<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 */
class CategoryAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with category composite data.
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
                'Orm\Zed\Category\Persistence\SpyCategoryAttribute',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryAttribute')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Category\Persistence\SpyCategory')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Category\Persistence\SpyCategoryClosureTable',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryClosureTable')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryNode')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Category\Persistence\SpyCategoryNode',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryNode')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Category\Persistence\SpyCategory')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Category\Persistence\SpyCategoryStore',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryStore')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Category\Persistence\SpyCategory')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Category\Persistence\SpyCategory',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Category\Persistence\SpyCategory')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Category\Persistence\SpyCategoryTemplate',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryTemplate')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
