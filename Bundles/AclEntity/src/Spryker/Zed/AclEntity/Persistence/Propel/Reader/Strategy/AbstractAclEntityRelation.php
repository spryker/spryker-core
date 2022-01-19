<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\ServiceContainer\ServiceContainerInterface;
use Spryker\Zed\AclEntity\Persistence\Exception\RelationNotFoundException;

class AbstractAclEntityRelation
{
    /**
     * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface
     */
    protected $propelServiceContainer;

    /**
     * @param \Propel\Runtime\ServiceContainer\ServiceContainerInterface $propelServiceContainer
     */
    public function __construct(ServiceContainerInterface $propelServiceContainer)
    {
        $this->propelServiceContainer = $propelServiceContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\RelationNotFoundException
     *
     * @return \Propel\Runtime\Map\RelationMap
     */
    protected function getRelationMap(AclEntityMetadataTransfer $aclEntityMetadataTransfer): RelationMap
    {
        $entityTableMap = $this->getTableMapByEntityClass($aclEntityMetadataTransfer->getEntityNameOrFail());
        $parentShortClass = $this->getShortClassName(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
        );
        foreach ($entityTableMap->getRelations() as $relationMap) {
            if ($relationMap->getRightTable()->getPhpName() === $parentShortClass) {
                return $relationMap;
            }
        }

        throw new RelationNotFoundException($parentShortClass, $aclEntityMetadataTransfer->getEntityNameOrFail());
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
     * @param string $relatedClass
     *
     * @return string
     */
    protected function getShortClassName(string $relatedClass): string
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
        return $this->propelServiceContainer->getDatabaseMap()->getTableByPhpName($entityClass);
    }
}
