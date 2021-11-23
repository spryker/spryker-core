<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;

class ForeignKeyRelationResolverStrategy extends AbstractRelationResolverStrategy
{
    /**
     * @var string
     */
    protected const RELATION_GETTER_TEMPLATE = 'get%s';

    /**
     * @var string
     */
    protected const ENTITY_PREFIX_DEFAULT = 'Spy';

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getRelations(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ObjectCollection {
        $query = PropelQuery::from($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail());
        $parentRelationMap = $this->getRelationMap($aclEntityMetadataTransfer);
        if ($parentRelationMap->getType() === RelationMap::ONE_TO_MANY) {
            /** @var \Propel\Runtime\Map\ColumnMap $columnMap */
            $columnMap = current($parentRelationMap->getRightColumns());
            $foreignKeyColumnName = $columnMap->getPhpName();
            $query->filterBy($foreignKeyColumnName, $entity->getPrimaryKey());

            return $query->find();
        }

        /** @var \Propel\Runtime\Map\ColumnMap $columnMap */
        $columnMap = current($parentRelationMap->getLocalColumns());
        $primaryKeyColumnName = $columnMap->getPhpName();
        $parentPrimaryKey = $entity->getByName($primaryKeyColumnName, TableMap::TYPE_PHPNAME);

        $query->filterByPrimaryKey($parentPrimaryKey);

        return $query->find();
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function generateAclEntityJoin(ModelCriteria $query, AclEntityMetadataTransfer $aclEntityMetadataTransfer): Join
    {
        $relationMap = $this->getRelationMap($aclEntityMetadataTransfer);
        $callable = function (ColumnMap $columnMap): string {
            return $columnMap->getFullyQualifiedName();
        };
        $join = new ModelJoin(
            array_map($callable, $relationMap->getLeftColumns()),
            array_map($callable, $relationMap->getRightColumns()),
        );

        $join->setTableMap($relationMap->getRightTable());

        return $this->updateJoinAliases($query, $join);
    }
}
