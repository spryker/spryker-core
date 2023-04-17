<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PickingList\Business\Assigner\PickingListUserAssigner;
use Spryker\Zed\PickingList\Business\Assigner\PickingListUserAssignerInterface;
use Spryker\Zed\PickingList\Business\Creator\PickingListCreator;
use Spryker\Zed\PickingList\Business\Creator\PickingListCreatorInterface;
use Spryker\Zed\PickingList\Business\Distinguisher\PickingListDistinguisher;
use Spryker\Zed\PickingList\Business\Distinguisher\PickingListDistinguisherInterface;
use Spryker\Zed\PickingList\Business\Expander\PickingListExpander;
use Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface;
use Spryker\Zed\PickingList\Business\Extractor\PickingListExtractor;
use Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface;
use Spryker\Zed\PickingList\Business\Extractor\WarehouseExtractor;
use Spryker\Zed\PickingList\Business\Extractor\WarehouseExtractorInterface;
use Spryker\Zed\PickingList\Business\Filter\PickingListFilter;
use Spryker\Zed\PickingList\Business\Filter\PickingListFilterInterface;
use Spryker\Zed\PickingList\Business\Generator\PickingListGenerator;
use Spryker\Zed\PickingList\Business\Generator\PickingListGeneratorInterface;
use Spryker\Zed\PickingList\Business\Grouper\PickingListGrouper;
use Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface;
use Spryker\Zed\PickingList\Business\Grouper\WarehouseUserAssignmentGrouper;
use Spryker\Zed\PickingList\Business\Grouper\WarehouseUserAssignmentGrouperInterface;
use Spryker\Zed\PickingList\Business\Mapper\PickingListMapper;
use Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface;
use Spryker\Zed\PickingList\Business\Reader\PickingListReader;
use Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface;
use Spryker\Zed\PickingList\Business\Reader\WarehouseUserAssignmentReader;
use Spryker\Zed\PickingList\Business\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Zed\PickingList\Business\StatusGenerator\PickingListStatusGenerator;
use Spryker\Zed\PickingList\Business\StatusGenerator\PickingListStatusGeneratorInterface;
use Spryker\Zed\PickingList\Business\Updater\PickingListUpdater;
use Spryker\Zed\PickingList\Business\Updater\PickingListUpdaterInterface;
use Spryker\Zed\PickingList\Business\Validator\PickingListStatusValidator;
use Spryker\Zed\PickingList\Business\Validator\PickingListStatusValidatorInterface;
use Spryker\Zed\PickingList\Business\Validator\PickingListValidatorComposite;
use Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeInterface;
use Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListDuplicatedPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListExistsPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListPickedByAnotherUserPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListWarehouseUserAssignmentValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemDuplicatedPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemExistsPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule;
use Spryker\Zed\PickingList\Dependency\External\PickingListToDatabaseConnectionInterface;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeInterface;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToWarehouseUserInterface;
use Spryker\Zed\PickingList\PickingListDependencyProvider;

/**
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 * @method \Spryker\Zed\PickingList\Persistence\PickingListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface getRepository()
 */
class PickingListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface
     */
    public function createPickingListReader(): PickingListReaderInterface
    {
        return new PickingListReader(
            $this->getRepository(),
            $this->createPickingListExpander(),
            $this->getPickingListCollectionExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Reader\WarehouseUserAssignmentReaderInterface
     */
    public function createWarehouseUserAssignmentReader(): WarehouseUserAssignmentReaderInterface
    {
        return new WarehouseUserAssignmentReader(
            $this->getWarehouseUserAssignmentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Creator\PickingListCreatorInterface
     */
    public function createPickingListCreator(): PickingListCreatorInterface
    {
        return new PickingListCreator(
            $this->createPickingListFilter(),
            $this->createPickingListCreatorValidator(),
            $this->getEntityManager(),
            $this->createPickingListStatusGenerator(),
            $this->getDatabaseConnection(),
            $this->createPickingListDistinguisher(),
            $this->getPickingListPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Updater\PickingListUpdaterInterface
     */
    public function createPickingListUpdater(): PickingListUpdaterInterface
    {
        return new PickingListUpdater(
            $this->createPickingListFilter(),
            $this->getEntityManager(),
            $this->createPickingListStatusGenerator(),
            $this->createPickingListUpdaterValidator(),
            $this->getDatabaseConnection(),
            $this->createPickingListDistinguisher(),
            $this->getPickingListPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Generator\PickingListGeneratorInterface
     */
    public function createPickingListGenerator(): PickingListGeneratorInterface
    {
        return new PickingListGenerator(
            $this->createPickingListCreator(),
            $this->createPickingListGrouper(),
            $this->createWarehouseExtractor(),
            $this->getPickingListGeneratorStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Filter\PickingListFilterInterface
     */
    public function createPickingListFilter(): PickingListFilterInterface
    {
        return new PickingListFilter();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\StatusGenerator\PickingListStatusGeneratorInterface
     */
    public function createPickingListStatusGenerator(): PickingListStatusGeneratorInterface
    {
        return new PickingListStatusGenerator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeInterface
     */
    public function createPickingListCreatorValidator(): PickingListValidatorCompositeInterface
    {
        return new PickingListValidatorComposite(
            $this->createPickingListReader(),
            $this->createPickingListGrouper(),
            $this->getCreatePickingListValidatorCompositeRules(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeInterface
     */
    public function createPickingListUpdaterValidator(): PickingListValidatorCompositeInterface
    {
        return new PickingListValidatorComposite(
            $this->createPickingListReader(),
            $this->createPickingListGrouper(),
            $this->getUpdatePickingListValidatorCompositeRules(),
        );
    }

    /**
     * @return list<\Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface>
     */
    public function getCreatePickingListValidatorCompositeRules(): array
    {
        return [
            $this->createPickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface>
     */
    public function getUpdatePickingListValidatorCompositeRules(): array
    {
        return [
            $this->createPickingListDuplicatedPickingListValidatorCompositeRule(),
            $this->createPickingListItemDuplicatedPickingListValidatorCompositeRule(),
            $this->createPickingListExistsPickingListValidatorCompositeRule(),
            $this->createPickingListItemExistsPickingListValidatorCompositeRule(),
            $this->createPickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule(),
            $this->createPickingListPickedByAnotherUserPickingListValidatorCompositeRule(),
            $this->createPickingListWarehouseUserAssignmentValidatorCompositeRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListDuplicatedPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListDuplicatedPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListItemDuplicatedPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListItemDuplicatedPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListExistsPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListExistsPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListItemExistsPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListItemExistsPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListPickedByAnotherUserPickingListValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListPickedByAnotherUserPickingListValidatorCompositeRule();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface
     */
    public function createPickingListWarehouseUserAssignmentValidatorCompositeRule(): PickingListValidatorCompositeRuleInterface
    {
        return new PickingListWarehouseUserAssignmentValidatorCompositeRule(
            $this->createPickingListExtractor(),
            $this->createWarehouseUserAssignmentReader(),
            $this->createWarehouseUserAssignmentGrouper(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Validator\PickingListStatusValidatorInterface
     */
    public function createPickingListStatusValidator(): PickingListStatusValidatorInterface
    {
        return new PickingListStatusValidator(
            $this->getRepository(),
            $this->createPickingListMapper(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface
     */
    public function createPickingListMapper(): PickingListMapperInterface
    {
        return new PickingListMapper();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface
     */
    public function createPickingListGrouper(): PickingListGrouperInterface
    {
        return new PickingListGrouper();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Grouper\WarehouseUserAssignmentGrouperInterface
     */
    public function createWarehouseUserAssignmentGrouper(): WarehouseUserAssignmentGrouperInterface
    {
        return new WarehouseUserAssignmentGrouper();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Extractor\WarehouseExtractorInterface
     */
    public function createWarehouseExtractor(): WarehouseExtractorInterface
    {
        return new WarehouseExtractor();
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface
     */
    public function createPickingListExpander(): PickingListExpanderInterface
    {
        return new PickingListExpander($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Distinguisher\PickingListDistinguisherInterface
     */
    public function createPickingListDistinguisher(): PickingListDistinguisherInterface
    {
        return new PickingListDistinguisher(
            $this->createPickingListExtractor(),
            $this->getRepository(),
            $this->createPickingListGrouper(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeInterface
     */
    public function getSalesFacade(): PickingListToSalesFacadeInterface
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface
     */
    public function createPickingListExtractor(): PickingListExtractorInterface
    {
        return new PickingListExtractor();
    }

    /**
     * @return \Spryker\Zed\PickingList\Dependency\Facade\PickingListToWarehouseUserInterface
     */
    public function getWarehouseUserAssignmentFacade(): PickingListToWarehouseUserInterface
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::FACADE_WAREHOUSE_USER);
    }

    /**
     * @return \Spryker\Zed\PickingList\Business\Assigner\PickingListUserAssignerInterface
     */
    public function createPickingListUserAssigner(): PickingListUserAssignerInterface
    {
        return new PickingListUserAssigner(
            $this->createPickingListReader(),
            $this->createPickingListUpdater(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Dependency\External\PickingListToDatabaseConnectionInterface
     */
    public function getDatabaseConnection(): PickingListToDatabaseConnectionInterface
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::CONNECTION_DATABASE);
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface>
     */
    public function getPickingListPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::PLUGINS_PICKING_LIST_POST_CREATE);
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface>
     */
    public function getPickingListPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::PLUGINS_PICKING_LIST_POST_UPDATE);
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface>
     */
    public function getPickingListGeneratorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::PLUGINS_PICKING_LIST_GENERATOR_STRATEGY);
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface>
     */
    public function getPickingListCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PickingListDependencyProvider::PLUGINS_PICKING_LIST_COLLECTION_EXPANDER);
    }
}
