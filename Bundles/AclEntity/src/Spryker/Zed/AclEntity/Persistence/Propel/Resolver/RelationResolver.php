<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Resolver;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\RelationMap;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class RelationResolver implements RelationResolverInterface
{
    /**
     * @var string
     */
    protected const RELATION_GETTER_TEMPLATE = 'get%s';

    /**
     * @var array<\Closure>
     */
    protected $strategyContainer;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @param array<\Closure> $strategyContainer
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     */
    public function __construct(array $strategyContainer, AclEntityMetadataReaderInterface $aclEntityMetadataReader)
    {
        $this->strategyContainer = $strategyContainer;
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
    }

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
    ): ObjectCollection {
        return $this->getStrategy($aclEntityMetadataTransfer)->getRelations($entity, $aclEntityMetadataTransfer);
    }

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
    ): ObjectCollection {
        if (!$aclEntityMetadataTransfer->getIsSubEntity()) {
            return (new ObjectCollection([$entity]));
        }

        $rootRelations = [];
        /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $relation */
        foreach ($this->getRelationsByAclEntityMetadata($entity, $aclEntityMetadataTransfer) as $relation) {
            $relationMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                get_class($relation),
            );

            $rootRelations = array_merge(
                $rootRelations,
                $this->getRootRelationsByAclEntityMetadata($relation, $relationMetadataTransfer)->getData(),
            );
        }

        return new ObjectCollection($rootRelations);
    }

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
    ): ModelCriteria {
        $strategy = $this->getStrategy($aclEntityMetadataTransfer);

        return $strategy->joinRelation($query, $aclEntityMetadataTransfer);
    }

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
    ): ModelCriteria {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );
        while ($aclEntityMetadataTransfer->getEntityNameOrFail() !== $parentAclEntityMetadataTransfer->getEntityNameOrFail()) {
            $query = $this->joinRelation($query, $aclEntityMetadataTransfer);
            $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $query;
    }

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
    ): ModelCriteria {
        while ($aclEntityMetadataTransfer->getIsSubEntity()) {
            $query = $this->joinRelation($query, $aclEntityMetadataTransfer);

            $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $query;
    }

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
    ): ModelCriteria {
        while ($aclEntityMetadataTransfer->getParent()) {
            $query = $this->joinRelation($query, $aclEntityMetadataTransfer);
            $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );
        }

        return $query;
    }

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Propel\Runtime\Map\RelationMap $relationMap
     *
     * @throws \InvalidArgumentException
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    public function getRelationsByRelationMap(ActiveRecordInterface $entity, RelationMap $relationMap): Collection
    {
        if (in_array($relationMap->getType(), [RelationMap::MANY_TO_MANY, RelationMap::ONE_TO_MANY])) {
            $relationGetter = sprintf(static::RELATION_GETTER_TEMPLATE, $relationMap->getPluralName());
            $callable = [$entity, $relationGetter];

            if (!is_callable($callable)) {
                throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
            }

            return call_user_func($callable);
        }

        $relationCollection = new ObjectCollection();
        $relationGetter = sprintf(static::RELATION_GETTER_TEMPLATE, $relationMap->getName());
        $callable = [$entity, $relationGetter];

        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
        }

        $relation = call_user_func($callable);

        if ($relation) {
            $relationCollection->append($relation);
        }

        return $relationCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy
     */
    protected function getStrategy(
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): AbstractRelationResolverStrategy {
        $strategy = call_user_func($this->strategyContainer[RelationResolverInterface::STRATEGY_FOREIGN_KEY]);
        if (
            !$aclEntityMetadataTransfer->getParent()
            || !$aclEntityMetadataTransfer->getParentOrFail()->getConnection()
        ) {
            return $strategy;
        }

        if ($this->isPivotTableConnectionStrategy($aclEntityMetadataTransfer)) {
            return $this->getPivotTableConnectionStrategy();
        }

        return call_user_func($this->strategyContainer[RelationResolverInterface::STRATEGY_REFERENCE_COLUMN]);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return bool
     */
    protected function isPivotTableConnectionStrategy(AclEntityMetadataTransfer $aclEntityMetadataTransfer): bool
    {
        return (bool)$aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityName();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy
     */
    protected function getPivotTableConnectionStrategy(): AbstractRelationResolverStrategy
    {
        return call_user_func($this->strategyContainer[RelationResolverInterface::STRATEGY_PIVOT_TABLE]);
    }
}
