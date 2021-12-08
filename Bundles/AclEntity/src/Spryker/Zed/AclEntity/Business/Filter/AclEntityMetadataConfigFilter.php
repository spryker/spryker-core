<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Filter;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

class AclEntityMetadataConfigFilter implements AclEntityMetadataConfigFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function filter(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            return $aclEntityMetadataConfigTransfer;
        }

        $aclEntityMetadataConfigTransfer = $this->filterAclEntityMetadataCollection($aclEntityMetadataConfigTransfer);

        return $this->filterAllowList($aclEntityMetadataConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function filterAclEntityMetadataCollection(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataCollectionTransfer = new AclEntityMetadataCollectionTransfer();
        /** @var \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer */
        foreach ($aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->getCollection() as $class => $aclEntityMetadataTransfer) {
            if (!class_exists($aclEntityMetadataTransfer->getEntityNameOrFail())) {
                continue;
            }
            $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
                $class,
                $aclEntityMetadataTransfer,
            );
        }

        return $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection($aclEntityMetadataCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function filterAllowList(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $allowList = [];
        foreach ($aclEntityMetadataConfigTransfer->getAclEntityAllowList() as $item) {
            if (class_exists($item)) {
                $allowList[] = $item;
            }
        }

        return $aclEntityMetadataConfigTransfer->setAclEntityAllowList($allowList);
    }
}
