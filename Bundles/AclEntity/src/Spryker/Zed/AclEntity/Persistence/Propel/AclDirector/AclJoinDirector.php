<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ServiceContainer\ServiceContainerInterface;
use Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclJoinResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class AclJoinDirector implements AclJoinDirectorInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclJoinResolverInterface
     */
    protected $aclJoinResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface
     */
    protected $propelServiceContainer;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclJoinResolverInterface $aclJoinResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Propel\Runtime\ServiceContainer\ServiceContainerInterface $propelServiceContainer
     */
    public function __construct(
        AclJoinResolverInterface $aclJoinResolver,
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        ServiceContainerInterface $propelServiceContainer
    ) {
        $this->aclJoinResolver = $aclJoinResolver;
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->propelServiceContainer = $propelServiceContainer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function applyAclRuleOnSelectQueryRelations(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ModelCriteria {
        foreach ($query->getJoins() as $join) {
            $joinTable = $join->getRightTableName();
            if (!$joinTable) {
                throw new QueryMergerJoinMalfunctionException();
            }
            $joinClass = $this->getModelClass($joinTable);
            if ($this->aclEntityMetadataReader->isAllowListItem($joinClass)) {
                continue;
            }
            $aclJoin = $this->aclJoinResolver->resolve($join);
            $query = $aclJoin->applyAclRuleOnSelectQueryRelation(
                $query,
                $join,
                $aclEntityRuleCollectionTransfer,
            );
        }

        return $query;
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected function getModelClass(string $table): string
    {
        $class = $this->propelServiceContainer->getDatabaseMap()->getTable($table)->getClassName();

        return strpos($class, '\\') === 0 ? substr($class, 1) : $class;
    }
}
