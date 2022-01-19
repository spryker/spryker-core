<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Reader;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Map\RelationMap;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver\AclEntityRelationResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class AclRelationReader implements AclRelationReaderInterface
{
    /**
     * @var string
     */
    protected const RELATION_GETTER_TEMPLATE = 'get%s';

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver\AclEntityRelationResolverInterface
     */
    protected $aclEntityRelationResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver\AclEntityRelationResolverInterface $aclEntityRelationResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     */
    public function __construct(
        AclEntityRelationResolverInterface $aclEntityRelationResolver,
        AclEntityMetadataReaderInterface $aclEntityMetadataReader
    ) {
        $this->aclEntityRelationResolver = $aclEntityRelationResolver;
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
    }

    /**
     * @phpstan-return \Propel\Runtime\Collection\Collection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    public function getRelationsByAclEntityMetadata(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): Collection {
        $aclEntityRelation = $this->aclEntityRelationResolver->resolve($aclEntityMetadataTransfer);

        return $aclEntityRelation->getRelations($entity, $aclEntityMetadataTransfer);
    }

    /**
     * @phpstan-return \Propel\Runtime\Collection\Collection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    public function getRootRelationsByAclEntityMetadata(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): Collection {
        if (!$aclEntityMetadataTransfer->getIsSubEntity()) {
            return (new Collection([$entity]));
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

        return new Collection($rootRelations);
    }

    /**
     * @phpstan-return \Propel\Runtime\Collection\Collection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
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

        $relationCollection = new Collection();
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
}
