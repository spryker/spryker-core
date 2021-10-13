<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity\Plugin;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface;

class AclEntityMetadataConfigWithWrongParentEntityExpanderPluginMock implements AclEntityMetadataConfigExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(
            (new AclEntityMetadataCollectionTransfer())
                ->addAclEntityMetadata(
                    SpyMerchant::class,
                    (new AclEntityMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setIsSubEntity(true)
                        ->setParent(
                            (new AclEntityParentMetadataTransfer())
                                ->setEntityName('test')
                        )
                )
        );
    }
}
