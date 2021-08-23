<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Propel\Generator\Model\Database;
use Propel\Generator\Model\Table;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilter;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirector;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirectorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\DefaultScopeAclQueryDirectorStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\GlobalScopeAclQueryDirectorStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\InheritedScopeAclQueryDirectorStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\SegmentScopeAclQueryDirectorStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclDirectorStrategyResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclDirectorStrategyResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Builder\ConnectorTableBuilder;
use Spryker\Zed\AclEntity\Persistence\Propel\Builder\ConnectorTableBuilderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Mapper\AclEntityRuleMapper;
use Spryker\Zed\AclEntity\Persistence\Propel\Mapper\AclEntitySegmentMapper;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMerger;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\ForeignKeyRelationResolverStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\PivotTableRelationResolverStrategy;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\ReferenceColumnRelationResolverStrategy;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReader;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferTransferSorter;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityRepository getRepository()
 */
class AclEntityPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirectorInterface
     */
    public function createAclQueryDirector(
        AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
    ): AclQueryDirectorInterface {
        return new AclQueryDirector(
            $this->getRepository(),
            $this->createAclDirectorStrategyResolver($aclEntityMetadataCollectionTransfer),
            $this->createAclEntityMetadataReader($aclEntityMetadataCollectionTransfer),
            $this->createRelationResolver($aclEntityMetadataCollectionTransfer),
            $this->getUserFacade(),
            $this->getAclFacade(),
            $this->createAclEntityQueryMerger()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    public function createAclEntityQueryMerger(): AclEntityQueryMergerInterface
    {
        return new AclEntityQueryMerger();
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclDirectorStrategyResolverInterface
     */
    public function createAclDirectorStrategyResolver(
        AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
    ): AclDirectorStrategyResolverInterface {
        $strategyContainer = [];
        $strategyContainer[AclEntityConstants::SCOPE_GLOBAL] =
            function (AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer) {
                return $this->createGlobalScopeQueryDirectorStrategy($aclEntityRuleCollectionTransfer);
            };
        $strategyContainer[AclEntityConstants::SCOPE_SEGMENT] =
            function (AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer) {
                return $this->createSegmentScopeAclQueryDirectorStrategy($aclEntityRuleCollectionTransfer);
            };
        $strategyContainer[AclEntityConstants::SCOPE_INHERITED] =
            function (AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer) use ($aclEntityMetadataCollectionTransfer) {
                return $this->createInheritedScopeAclQueryDirectorStrategy($aclEntityRuleCollectionTransfer, $aclEntityMetadataCollectionTransfer);
            };
        $strategyContainer[AclEntityConstants::SCOPE_DEFAULT] =
            function () use ($aclEntityMetadataCollectionTransfer) {
                return $this->createDefaultScopeAclQueryDirectorStrategy($aclEntityMetadataCollectionTransfer);
            };

        return new AclDirectorStrategyResolver(
            $strategyContainer,
            $this->createAclEntityRuleCollectionTransferFilter(),
            $this->createAclEntityRuleCollectionTransferSorter()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Mapper\AclEntitySegmentMapper
     */
    public function createAclEntitySegmentMapper(): AclEntitySegmentMapper
    {
        return new AclEntitySegmentMapper();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Mapper\AclEntityRuleMapper
     */
    public function createAclEntityRuleMapper(): AclEntityRuleMapper
    {
        return new AclEntityRuleMapper($this->getAclEntityService());
    }

    /**
     * @phpstan-return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment>
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery
     */
    public function createAclEntitySegmentQuery(): SpyAclEntitySegmentQuery
    {
        return SpyAclEntitySegmentQuery::create();
    }

    /**
     * @phpstan-return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule>
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery
     */
    public function createAclEntityRuleQuery(): SpyAclEntityRuleQuery
    {
        return SpyAclEntityRuleQuery::create();
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function createGlobalScopeQueryDirectorStrategy(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): AclQueryDirectorStrategyInterface {
        return new GlobalScopeAclQueryDirectorStrategy($aclEntityRuleCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function createSegmentScopeAclQueryDirectorStrategy(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): AclQueryDirectorStrategyInterface {
        return new SegmentScopeAclQueryDirectorStrategy(
            $aclEntityRuleCollectionTransfer,
            $this->getAclEntityService(),
            $this->createAclEntityRuleCollectionTransferFilter()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function createInheritedScopeAclQueryDirectorStrategy(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
    ): AclQueryDirectorStrategyInterface {
        $strategyContainer = [];
        $strategyContainer[AclEntityConstants::SCOPE_SEGMENT] =
            function (AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer) {
                return $this->createSegmentScopeAclQueryDirectorStrategy($aclEntityRuleCollectionTransfer);
            };
        $strategyContainer[AclEntityConstants::SCOPE_DEFAULT] =
            function () use ($aclEntityMetadataCollectionTransfer) {
                return $this->createDefaultScopeAclQueryDirectorStrategy($aclEntityMetadataCollectionTransfer);
            };

        return new InheritedScopeAclQueryDirectorStrategy(
            $aclEntityRuleCollectionTransfer,
            $this->createAclEntityMetadataReader($aclEntityMetadataCollectionTransfer),
            $this->createRelationResolver($aclEntityMetadataCollectionTransfer),
            $this->createAclEntityRuleCollectionTransferFilter(),
            $this->createAclEntityRuleCollectionTransferSorter(),
            $this->createAclEntityQueryMerger(),
            $strategyContainer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function createDefaultScopeAclQueryDirectorStrategy(
        AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
    ): AclQueryDirectorStrategyInterface {
        return new DefaultScopeAclQueryDirectorStrategy($this->createAclEntityMetadataReader($aclEntityMetadataCollectionTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    public function createAclEntityMetadataReader(
        AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
    ): AclEntityMetadataReaderInterface {
        return new AclEntityMetadataReader(
            $aclEntityMetadataCollectionTransfer,
            $this->getConfig()->getDefaultGlobalOperationMask()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface
     */
    public function createRelationResolver(AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer): RelationResolverInterface
    {
        $strategyContainer = [];
        $strategyContainer[RelationResolverInterface::STRATEGY_FOREIGN_KEY] = function () {
            return $this->createForeignKeyRelationResolverStrategy();
        };
        $strategyContainer[RelationResolverInterface::STRATEGY_REFERENCE_COLUMN] = function () {
            return $this->createReferenceColumnRelationResolverStrategy();
        };
        $strategyContainer[RelationResolverInterface::STRATEGY_PIVOT_TABLE] = function () {
            return $this->createPivotTableRelationResolverStrategy();
        };

        return new RelationResolver(
            $strategyContainer,
            $this->createAclEntityMetadataReader($aclEntityMetadataCollectionTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy
     */
    public function createForeignKeyRelationResolverStrategy(): AbstractRelationResolverStrategy
    {
        return new ForeignKeyRelationResolverStrategy();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy
     */
    public function createReferenceColumnRelationResolverStrategy(): AbstractRelationResolverStrategy
    {
        return new ReferenceColumnRelationResolverStrategy();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy\AbstractRelationResolverStrategy
     */
    public function createPivotTableRelationResolverStrategy(): AbstractRelationResolverStrategy
    {
        return new PivotTableRelationResolverStrategy();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    public function createAclEntityRuleCollectionTransferFilter(): AclEntityRuleCollectionTransferFilterInterface
    {
        return new AclEntityRuleCollectionTransferFilter();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface
     */
    public function createAclEntityRuleCollectionTransferSorter(): AclEntityRuleCollectionTransferSorterInterface
    {
        return new AclEntityRuleCollectionTransferTransferSorter($this->getConfig()->getScopePriority());
    }

    /**
     * @return \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    public function getAclEntityService(): AclEntityServiceInterface
    {
        return $this->getProvidedDependency(AclEntityDependencyProvider::SERVICE_ACL_ENTITY);
    }

    /**
     * @param \Propel\Generator\Model\Table $baseTable
     * @param \Propel\Generator\Model\Database $database
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Builder\ConnectorTableBuilderInterface
     */
    public function createConnectorTableBuilder(Table $baseTable, Database $database): ConnectorTableBuilderInterface
    {
        return new ConnectorTableBuilder($baseTable, $database, $this->getAclEntityService());
    }

    /**
     * @return \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface
     */
    protected function getUserFacade(): AclEntityToUserFacadeBridgeInterface
    {
        return $this->getProvidedDependency(AclEntityDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface
     */
    public function getAclFacade(): AclEntityToAclFacadeBridgeInterface
    {
        return $this->getProvidedDependency(AclEntityDependencyProvider::FACADE_ACL);
    }
}
