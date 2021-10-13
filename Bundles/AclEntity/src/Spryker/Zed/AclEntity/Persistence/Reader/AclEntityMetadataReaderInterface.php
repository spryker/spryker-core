<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Reader;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;

interface AclEntityMetadataReaderInterface
{
    /**
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findAclEntityMetadataTransferForEntityClass(string $entityClass): ?AclEntityMetadataTransfer;

    /**
     * @param string $entitySubClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findRootAclEntityMetadataTransferForEntitySubClass(string $entitySubClass): ?AclEntityMetadataTransfer;

    /**
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    public function findRootAclEntityMetadataTransferForEntityClass(string $entityClass): ?AclEntityMetadataTransfer;

    /**
     * @param string $entitySubClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer
     */
    public function getRootAclEntityMetadataTransferForEntitySubClass(string $entitySubClass): AclEntityMetadataTransfer;

    /**
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer
     */
    public function getRootAclEntityMetadataTransferForEntityClass(string $entityClass): AclEntityMetadataTransfer;

    /**
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer
     */
    public function getAclEntityMetadataTransferForEntityClass(string $entityClass): AclEntityMetadataTransfer;

    /**
     * @param string $entityClass
     *
     * @return int
     */
    public function getDefaultOperationMaskForEntityClass(string $entityClass): int;
}
