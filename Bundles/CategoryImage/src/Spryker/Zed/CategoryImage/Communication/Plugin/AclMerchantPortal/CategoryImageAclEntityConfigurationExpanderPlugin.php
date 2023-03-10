<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 */
class CategoryImageAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with category image composite data.
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
                'Orm\Zed\CategoryImage\Persistence\SpyCategoryImage',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImage')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Category\Persistence\SpyCategory')),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
