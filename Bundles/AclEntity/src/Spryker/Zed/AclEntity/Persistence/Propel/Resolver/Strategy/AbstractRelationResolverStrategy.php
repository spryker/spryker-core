<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;

abstract class AbstractRelationResolverStrategy
{
    /**
     * @var string
     */
    protected const RELATION_TEMPLATE = '%s.%s';
    /**
     * @var string
     */
    protected const RELATION_ALIAS_TEMPLATE = '%s%s';
    /**
     * @var string
     */
    protected const JOIN_SUFFIX = '_acl';

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    abstract public function getRelations(
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
    abstract public function joinRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria;

    /**
     * @param string $relatedClass
     *
     * @return string
     */
    protected function convertFullToShortClassName(string $relatedClass): string
    {
        return basename(str_replace('\\', '/', $relatedClass));
    }

    /**
     * @param string $entityClass
     *
     * @return \Propel\Runtime\Map\TableMap
     */
    protected function getTableMapByEntityClass(string $entityClass): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap()->getTableByPhpName($entityClass);
    }

    /**
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param string $entityClass
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function getQueryByEntityClass(string $entityClass): ModelCriteria
    {
        return PropelQuery::from($entityClass);
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function getRelationAlias(string $tableName): string
    {
        return sprintf(static::RELATION_ALIAS_TEMPLATE, $tableName, static::JOIN_SUFFIX);
    }
}
