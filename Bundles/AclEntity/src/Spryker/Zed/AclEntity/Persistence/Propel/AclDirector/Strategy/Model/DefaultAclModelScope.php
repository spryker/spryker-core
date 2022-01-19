<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class DefaultAclModelScope implements AclModelScopeInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     */
    public function __construct(AclEntityMetadataReaderInterface $aclEntityMetadataReader)
    {
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function isSupported(string $scope): bool
    {
        return $scope === AclEntityConstants::SCOPE_DEFAULT;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_CREATE) > 0;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isUpdatable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_UPDATE) > 0;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isDeletable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_DELETE) > 0;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isReadable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_READ) > 0;
    }
}
