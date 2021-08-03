<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Resolver;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\RelationMap;

interface RelationResolverInterface
{
    public const STRATEGY_FOREIGN_KEY = 'STRATEGY_FOREIGN_KEY';
    public const STRATEGY_PIVOT_TABLE = 'STRATEGY_PIVOT_TABLE';
    public const STRATEGY_REFERENCE_COLUMN = 'STRATEGY_REFERENCE_COLUMN';

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getRelationsByAclEntityMetadata(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ObjectCollection;

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getRootRelationsByAclEntityMetadata(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ObjectCollection;

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria;

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $parentAclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinEntityParent(
        ModelCriteria $query,
        AclEntityMetadataTransfer $parentAclEntityMetadataTransfer
    ): ModelCriteria;

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinSubEntityRootRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria;

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinEntityRootRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria;

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Propel\Runtime\Map\RelationMap $relationMap
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    public function getRelationsByRelationMap(
        ActiveRecordInterface $entity,
        RelationMap $relationMap
    ): Collection;
}
