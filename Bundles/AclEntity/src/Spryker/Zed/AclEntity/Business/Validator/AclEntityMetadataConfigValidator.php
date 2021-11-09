<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Validator;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigEntityNotFoundException;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigInvalidKeyException;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigParentEntityNotFoundException;

class AclEntityMetadataConfigValidator implements AclEntityMetadataConfigValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return void
     */
    public function validate(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): void
    {
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        if (!$aclEntityMetadataCollectionTransfer) {
            return;
        }
        $this->validateMetadataCollectionKeys($aclEntityMetadataCollectionTransfer);

        /** @var \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer */
        foreach ($aclEntityMetadataCollectionTransfer->getCollection() as $aclEntityMetadataTransfer) {
            $this->validateEntities($aclEntityMetadataTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigEntityNotFoundException
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigParentEntityNotFoundException
     *
     * @return void
     */
    protected function validateEntities(AclEntityMetadataTransfer $aclEntityMetadataTransfer): void
    {
        if (!class_exists($aclEntityMetadataTransfer->getEntityNameOrFail())) {
            throw new AclEntityMetadataConfigEntityNotFoundException($aclEntityMetadataTransfer->getEntityNameOrFail());
        }
        if ($aclEntityMetadataTransfer->getParent() && !class_exists($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail())) {
            throw new AclEntityMetadataConfigParentEntityNotFoundException(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigInvalidKeyException
     *
     * @return void
     */
    protected function validateMetadataCollectionKeys(AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer): void
    {
        /** @var \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer */
        foreach ($aclEntityMetadataCollectionTransfer->getCollection() as $aclEntityMetadataCollectionKey => $aclEntityMetadataTransfer) {
            if ($aclEntityMetadataCollectionKey !== $aclEntityMetadataTransfer->getEntityNameOrFail()) {
                throw new AclEntityMetadataConfigInvalidKeyException($aclEntityMetadataTransfer->getEntityNameOrFail());
            }
        }
    }
}
