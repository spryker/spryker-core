<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityDummyProduct\Business\Expander;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Shared\AclEntity\AclEntityConstants;

class AclEntityMetadataConfigExpander implements AclEntityMetadataConfigExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductStoreRelation(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProduct::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class),
                ),
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStore::class)
                ->setHasSegmentTable(true)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImage::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductImageSet::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setPivotEntityName(SpyProductImageSetToProductImage::class)
                                ->setReference('fk_product_image')
                                ->setReferencedColumn('fk_product_image_set'),
                        ),
                )
                ->setIsSubEntity(true),
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductLocalizedAttributes::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductLocalizedAttributes::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class),
                )
                ->setIsSubEntity(true),
        );

        return $aclEntityMetadataConfigTransfer;
    }
}
