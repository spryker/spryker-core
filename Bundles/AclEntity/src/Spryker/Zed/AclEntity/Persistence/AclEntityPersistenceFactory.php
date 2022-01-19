<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Propel\Generator\Model\Database;
use Propel\Generator\Model\Table;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\ServiceContainerInterface;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilter;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclJoinDirector;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclJoinDirectorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirector;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirectorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirector;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirectorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\InnerAclJoin;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\LeftAclJoin;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\RightAclJoin;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\DefaultAclModelScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\GlobalAclModelScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\InheritedAclModelScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\SegmentAclModelScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\DefaultAclQueryScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\GlobalAclQueryScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\InheritedAclQueryScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\SegmentAclQueryScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclJoinResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclJoinResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclModelScopeResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclModelScopeResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Builder\ConnectorTableBuilder;
use Spryker\Zed\AclEntity\Persistence\Propel\Builder\ConnectorTableBuilderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparator;
use Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpander;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\ForeignKeyEntityConnection;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\PivotTableEntityConnection;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\ReferenceColumnEntityConnection;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver\AclEntityConnectionResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver\AclEntityConnectionResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGenerator;
use Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Mapper\AclEntityRuleMapper;
use Spryker\Zed\AclEntity\Persistence\Propel\Mapper\AclEntitySegmentMapper;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProvider;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProvider;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMerger;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReader;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\ForeignKeyEntityRelation;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\PivotTableEntityRelation;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\ReferenceColumnEntityRelation;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver\AclEntityRelationResolver;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver\AclEntityRelationResolverInterface;
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
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirectorInterface
     */
    public function createAclQueryDirector(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclQueryDirectorInterface {
        return new AclQueryDirector(
            $this->createAclJoinDirector($aclEntityMetadataConfigTransfer),
            $this->createAclEntityRuleProvider(),
            $this->createAclQueryScopeResolver($aclEntityMetadataConfigTransfer),
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclQueryExpander($aclEntityMetadataConfigTransfer),
            $this->createAclEntityQueryMerger(),
            $this->createAclModelDirector($aclEntityMetadataConfigTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirectorInterface
     */
    public function createAclModelDirector(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclModelDirectorInterface {
        return new AclModelDirector(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclEntityRuleProvider(),
            $this->createAclModelScopeResolver($aclEntityMetadataConfigTransfer),
            $this->createAclRelationReader($aclEntityMetadataConfigTransfer),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclJoinDirectorInterface
     */
    public function createAclJoinDirector(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclJoinDirectorInterface
    {
        return new AclJoinDirector(
            $this->createAclJoinResolver($aclEntityMetadataConfigTransfer),
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface
     */
    public function createAclEntityRuleProvider(): AclEntityRuleProviderInterface
    {
        return new AclEntityRuleProvider($this->getAclRoleProvider(), $this->getRepository());
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    public function createAclEntityQueryMerger(): AclEntityQueryMergerInterface
    {
        return new AclEntityQueryMerger(
            $this->createJoinComparator(),
            $this->createQueryAliasGenerator(),
            $this->getAclEntityService(),
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface
     */
    public function createQueryAliasGenerator(): AclEntityAliasGeneratorInterface
    {
        return new AclEntityAliasGenerator();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface
     */
    public function createJoinComparator(): JoinComparatorInterface
    {
        return new JoinComparator();
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface
     */
    public function createAclQueryScopeResolver(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclQueryScopeResolverInterface {
        return new AclQueryScopeResolver(
            [
                $this->createGlobalScopeQueryDirector(),
                $this->createSegmentScopeAclQueryDirector(),
                $this->createInheritedScopeAclQueryDirector($aclEntityMetadataConfigTransfer),
                $this->createDefaultScopeAclQueryDirector($aclEntityMetadataConfigTransfer),
            ],
            $this->createAclEntityRuleCollectionTransferFilter(),
            $this->createAclEntityRuleCollectionTransferSorter(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclJoinResolverInterface
     */
    public function createAclJoinResolver(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclJoinResolverInterface {
        return new AclJoinResolver(
            [
                $this->createLeftAclJoin($aclEntityMetadataConfigTransfer),
                $this->createRightAclJoin($aclEntityMetadataConfigTransfer),
                $this->createInnerAclJoin($aclEntityMetadataConfigTransfer),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface
     */
    public function createInnerAclJoin(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclJoinInterface {
        return new InnerAclJoin(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclQueryScopeResolver($aclEntityMetadataConfigTransfer),
            $this->createAclQueryExpander($aclEntityMetadataConfigTransfer),
            $this->createAclEntityQueryMerger(),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface
     */
    public function createLeftAclJoin(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclJoinInterface {
        return new LeftAclJoin(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclQueryScopeResolver($aclEntityMetadataConfigTransfer),
            $this->createAclQueryExpander($aclEntityMetadataConfigTransfer),
            $this->createAclEntityQueryMerger(),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join\AclJoinInterface
     */
    public function createRightAclJoin(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclJoinInterface {
        return new RightAclJoin(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclQueryScopeResolver($aclEntityMetadataConfigTransfer),
            $this->createAclQueryExpander($aclEntityMetadataConfigTransfer),
            $this->createAclEntityQueryMerger(),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclModelScopeResolverInterface
     */
    public function createAclModelScopeResolver(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclModelScopeResolverInterface {
        return new AclModelScopeResolver(
            [
                $this->createGlobalAclModelScope(),
                $this->createSegmentAclModelScope(),
                $this->createInheritedAclModelScope($aclEntityMetadataConfigTransfer),
                $this->createDefaultAclModelScope($aclEntityMetadataConfigTransfer),
            ],
            $this->createAclEntityRuleCollectionTransferFilter(),
            $this->createAclEntityRuleCollectionTransferSorter(),
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    public function createGlobalAclModelScope(): AclModelScopeInterface
    {
        return new GlobalAclModelScope();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    public function createSegmentAclModelScope(): AclModelScopeInterface
    {
        return new SegmentAclModelScope(
            $this->getAclEntityService(),
            $this->createAclEntityRuleCollectionTransferFilter(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    public function createDefaultAclModelScope(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclModelScopeInterface {
        return new DefaultAclModelScope(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    public function createInheritedAclModelScope(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclModelScopeInterface {
        return new InheritedAclModelScope(
            $this->createAclEntityRuleCollectionTransferFilter(),
            $this->createAclRelationReader($aclEntityMetadataConfigTransfer),
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createSegmentAclModelScope(),
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
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    public function createGlobalScopeQueryDirector(): AclQueryScopeInterface
    {
        return new GlobalAclQueryScope();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    public function createSegmentScopeAclQueryDirector(): AclQueryScopeInterface
    {
        return new SegmentAclQueryScope(
            $this->getAclEntityService(),
            $this->createAclEntityRuleCollectionTransferFilter(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    public function createInheritedScopeAclQueryDirector(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclQueryScopeInterface {
        return new InheritedAclQueryScope(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclQueryExpander($aclEntityMetadataConfigTransfer),
            $this->createAclEntityRuleCollectionTransferFilter(),
            $this->createAclEntityRuleCollectionTransferSorter(),
            $this->createAclEntityQueryMerger(),
            [
                $this->createGlobalScopeQueryDirector(),
                $this->createSegmentScopeAclQueryDirector(),
                $this->createDefaultScopeAclQueryDirector($aclEntityMetadataConfigTransfer),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    public function createDefaultScopeAclQueryDirector(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclQueryScopeInterface {
        return new DefaultAclQueryScope($this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    public function createAclEntityMetadataReader(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataReaderInterface {
        return new AclEntityMetadataReader(
            $aclEntityMetadataConfigTransfer,
            $this->getConfig()->getDefaultGlobalOperationMask(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface
     */
    public function createAclQueryExpander(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclQueryExpanderInterface
    {
        return new AclQueryExpander(
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            $this->createAclEntityConnectionResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Expander\StrategyResolver\AclEntityConnectionResolverInterface
     */
    public function createAclEntityConnectionResolver(): AclEntityConnectionResolverInterface
    {
        return new AclEntityConnectionResolver(
            [
                $this->createForeignKeyAclEntityConnection(),
                $this->createReferenceColumnAclEntityConnection(),
                $this->createPivotTableAclEntityConnection(),
            ],
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface
     */
    public function createForeignKeyAclEntityConnection(): AclEntityConnectionInterface
    {
        return new ForeignKeyEntityConnection(
            $this->createJoinComparator(),
            $this->createQueryAliasGenerator(),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface
     */
    public function createReferenceColumnAclEntityConnection(): AclEntityConnectionInterface
    {
        return new ReferenceColumnEntityConnection(
            $this->createJoinComparator(),
            $this->createQueryAliasGenerator(),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy\AclEntityConnectionInterface
     */
    public function createPivotTableAclEntityConnection(): AclEntityConnectionInterface
    {
        return new PivotTableEntityConnection(
            $this->createJoinComparator(),
            $this->createQueryAliasGenerator(),
            $this->getPropelServiceContainer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface
     */
    public function createAclRelationReader(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclRelationReaderInterface
    {
        return new AclRelationReader(
            $this->createAclEntityRelationResolver(),
            $this->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\StrategyResolver\AclEntityRelationResolverInterface
     */
    public function createAclEntityRelationResolver(): AclEntityRelationResolverInterface
    {
        return new AclEntityRelationResolver(
            [
                $this->createForeignKeyEntityRelation(),
                $this->createPivotTableEntityRelation(),
                $this->createReferenceColumnEntityRelation(),
            ],
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface
     */
    public function createForeignKeyEntityRelation(): AclEntityRelationInterface
    {
        return new ForeignKeyEntityRelation($this->getPropelServiceContainer());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface
     */
    public function createPivotTableEntityRelation(): AclEntityRelationInterface
    {
        return new PivotTableEntityRelation($this->getPropelServiceContainer());
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\AclEntityRelationInterface
     */
    public function createReferenceColumnEntityRelation(): AclEntityRelationInterface
    {
        return new ReferenceColumnEntityRelation($this->getPropelServiceContainer());
    }

    /**
     * @return \Propel\Runtime\ServiceContainer\ServiceContainerInterface
     */
    public function getPropelServiceContainer(): ServiceContainerInterface
    {
        return Propel::getServiceContainer();
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
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface
     */
    public function getAclRoleProvider(): AclRoleProviderInterface
    {
        return AclRoleProvider::getInstance($this->getUserFacade(), $this->getAclFacade());
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
