<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Reader;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclEntity\Persistence\Exception\MissingMetadataException;
use Spryker\Zed\AclEntity\Persistence\Exception\MissingRootMetadataException;

class AclEntityMetadataReader implements AclEntityMetadataReaderInterface
{
    /**
     * @var \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected $aclEntityMetadataConfig;

    /**
     * @var int
     */
    protected $defaultGlobalOperationMask;

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfig
     * @param int $defaultGlobalOperationMask
     */
    public function __construct(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfig,
        int $defaultGlobalOperationMask
    ) {
        $this->aclEntityMetadataConfig = $aclEntityMetadataConfig;
        $this->defaultGlobalOperationMask = $defaultGlobalOperationMask;
    }

    /**
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findAclEntityMetadataTransferForEntityClass(string $entityClass): ?AclEntityMetadataTransfer
    {
        $aclEntityMetadataCollection = $this->aclEntityMetadataConfig->getAclEntityMetadataCollectionOrFail()->getCollection();

        return $aclEntityMetadataCollection[$entityClass] ?? null;
    }

    /**
     * @param string $entitySubClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findRootAclEntityMetadataTransferForEntitySubClass(string $entitySubClass): ?AclEntityMetadataTransfer
    {
        $aclEntityMetadataTransfer = $this->findAclEntityMetadataTransferForEntityClass($entitySubClass);
        while ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            if (!$aclEntityMetadataTransfer->getParent()) {
                return null;
            }

            $aclEntityMetadataTransfer = $this->findAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity() ? null : $aclEntityMetadataTransfer;
    }

    /**
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findRootAclEntityMetadataTransferForEntityClass(string $entityClass): ?AclEntityMetadataTransfer
    {
        $aclEntityMetadataTransfer = $this->findAclEntityMetadataTransferForEntityClass($entityClass);
        while ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getParent()) {
            $aclEntityMetadataTransfer = $this->findAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getParent() ? null : $aclEntityMetadataTransfer;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $connectionClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findAclEntityMetadataTransferByConnectionClass(string $connectionClass): ?AclEntityMetadataTransfer
    {
        /** @var \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer */
        foreach ($this->aclEntityMetadataConfig->getAclEntityMetadataCollectionOrFail()->getCollection() as $aclEntityMetadataTransfer) {
            if (
                !$aclEntityMetadataTransfer->getParent()
                || !$aclEntityMetadataTransfer->getParentOrFail()->getConnection()
            ) {
                continue;
            }
            $pivotEntity = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityName();
            if ($pivotEntity === $connectionClass) {
                return $aclEntityMetadataTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $entitySubClass
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\MissingRootMetadataException
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer
     */
    public function getRootAclEntityMetadataTransferForEntitySubClass(string $entitySubClass): AclEntityMetadataTransfer
    {
        $rootAclEntityMetadataTransfer = $this->findRootAclEntityMetadataTransferForEntitySubClass($entitySubClass);
        if (!$rootAclEntityMetadataTransfer) {
            throw new MissingRootMetadataException($entitySubClass);
        }

        return $rootAclEntityMetadataTransfer;
    }

    /**
     * @param string $entityClass
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\MissingRootMetadataException
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer
     */
    public function getRootAclEntityMetadataTransferForEntityClass(string $entityClass): AclEntityMetadataTransfer
    {
        $rootAclEntityMetadataTransfer = $this->findRootAclEntityMetadataTransferForEntityClass($entityClass);
        if (!$rootAclEntityMetadataTransfer) {
            throw new MissingRootMetadataException($entityClass);
        }

        return $rootAclEntityMetadataTransfer;
    }

    /**
     * @param string $entityClass
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\MissingMetadataException
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer
     */
    public function getAclEntityMetadataTransferForEntityClass(string $entityClass): AclEntityMetadataTransfer
    {
        $aclEntityMetadataTransfer = $this->findAclEntityMetadataTransferForEntityClass($entityClass);
        if (!$aclEntityMetadataTransfer) {
            throw new MissingMetadataException($entityClass);
        }

        return $aclEntityMetadataTransfer;
    }

    /**
     * @param string $entityClass
     *
     * @return int
     */
    public function getDefaultOperationMaskForEntityClass(string $entityClass): int
    {
        $aclEntityMetadataCollection = $this->aclEntityMetadataConfig->getAclEntityMetadataCollectionOrFail()->getCollection();

        if (!isset($aclEntityMetadataCollection[$entityClass])) {
            return $this->defaultGlobalOperationMask;
        }

        /** @var \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer */
        $aclEntityMetadataTransfer = $aclEntityMetadataCollection[$entityClass];

        return $aclEntityMetadataTransfer->getDefaultGlobalOperationMask() ?? $this->defaultGlobalOperationMask;
    }

    /**
     * @param string $entityClass
     *
     * @return bool
     */
    public function isAllowListItem(string $entityClass): bool
    {
        return in_array($entityClass, $this->aclEntityMetadataConfig->getAclEntityAllowList());
    }
}
