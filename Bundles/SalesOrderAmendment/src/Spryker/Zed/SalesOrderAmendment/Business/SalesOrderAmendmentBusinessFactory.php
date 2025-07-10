<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business;

use Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOrderAmendment\Business\Checker\CartChecker;
use Spryker\Zed\SalesOrderAmendment\Business\Checker\CartCheckerInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentCreator;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentCreatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentQuoteCreator;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentQuoteCreatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentDeleter;
use Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentDeleterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentQuoteDeleter;
use Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentQuoteDeleterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\CartReorderExpander;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\CartReorderExpanderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\OrderExpander;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\QuoteExpander;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\QuoteExpanderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilter;
use Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Hydrator\CartReorderItemHydrator;
use Spryker\Zed\SalesOrderAmendment\Business\Hydrator\CartReorderItemHydratorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapper;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapperInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentQuoteCriteriaMapper;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentQuoteCriteriaMapperInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReader;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReaderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentQuoteReader;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReader;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Replacer\SalesOrderItemReplacer;
use Spryker\Zed\SalesOrderAmendment\Business\Replacer\SalesOrderItemReplacerInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Resolver\SalesOrderAmendmentAvailabilityResolver;
use Spryker\Zed\SalesOrderAmendment\Business\Resolver\SalesOrderAmendmentAvailabilityResolverInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Saver\SalesOrderAmendmentQuoteSaver;
use Spryker\Zed\SalesOrderAmendment\Business\Saver\SalesOrderAmendmentQuoteSaverInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Strategy\GroupKeyQuantitySalesOrderAmendmentItemCollectorStrategy;
use Spryker\Zed\SalesOrderAmendment\Business\Strategy\SalesOrderAmendmentItemCollectorStrategyInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentQuoteUpdater;
use Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentQuoteUpdaterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentUpdater;
use Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentUpdaterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\CartReorderValidator;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\CartReorderValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\QuoteRequestValidator;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\QuoteRequestValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentQuoteValidator;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentQuoteValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidator;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdder;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToQuoteFacadeInterface;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface getRepository()
 */
class SalesOrderAmendmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface
     */
    public function createSalesOrderAmendmentReader(): SalesOrderAmendmentReaderInterface
    {
        return new SalesOrderAmendmentReader(
            $this->getRepository(),
            $this->getSalesOrderAmendmentExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentCreatorInterface
     */
    public function createSalesOrderAmendmentCreator(): SalesOrderAmendmentCreatorInterface
    {
        return new SalesOrderAmendmentCreator(
            $this->createSalesOrderAmendmentCreateValidator(),
            $this->getEntityManager(),
            $this->createSalesOrderAmendmentMapper(),
            $this->getSalesOrderAmendmentPreCreatePlugins(),
            $this->getSalesOrderAmendmentPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentQuoteCreatorInterface
     */
    public function createSalesOrderAmendmentQuoteCreator(): SalesOrderAmendmentQuoteCreatorInterface
    {
        return new SalesOrderAmendmentQuoteCreator(
            $this->getEntityManager(),
            $this->createQuoteFieldsFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentUpdaterInterface
     */
    public function createSalesOrderAmendmentUpdater(): SalesOrderAmendmentUpdaterInterface
    {
        return new SalesOrderAmendmentUpdater(
            $this->createSalesOrderAmendmentUpdateValidator(),
            $this->getEntityManager(),
            $this->getSalesOrderAmendmentPreUpdatePlugins(),
            $this->getSalesOrderAmendmentPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentQuoteUpdaterInterface
     */
    public function createSalesOrderAmendmentQuoteUpdater(): SalesOrderAmendmentQuoteUpdaterInterface
    {
        return new SalesOrderAmendmentQuoteUpdater(
            $this->getEntityManager(),
            $this->createQuoteFieldsFilter(),
            $this->createSalesOrderAmendmentQuoteValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentDeleterInterface
     */
    public function createSalesOrderAmendmentDeleter(): SalesOrderAmendmentDeleterInterface
    {
        return new SalesOrderAmendmentDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getSalesOrderAmendmentPreDeletePlugins(),
            $this->getSalesOrderAmendmentPostDeletePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentQuoteDeleterInterface
     */
    public function createSalesOrderAmendmentQuoteDeleter(): SalesOrderAmendmentQuoteDeleterInterface
    {
        return new SalesOrderAmendmentQuoteDeleter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createSalesOrderAmendmentQuoteCriteriaMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentQuoteCriteriaMapperInterface
     */
    public function createSalesOrderAmendmentQuoteCriteriaMapper(): SalesOrderAmendmentQuoteCriteriaMapperInterface
    {
        return new SalesOrderAmendmentQuoteCriteriaMapper();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Expander\CartReorderExpanderInterface
     */
    public function createCartReorderExpander(): CartReorderExpanderInterface
    {
        return new CartReorderExpander(
            $this->getSalesOrderAmendmentService(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->createSalesOrderAmendmentReader());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Expander\QuoteExpanderInterface
     */
    public function createQuoteExpander(): QuoteExpanderInterface
    {
        return new QuoteExpander($this->createOrderReader());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapperInterface
     */
    public function createSalesOrderAmendmentMapper(): SalesOrderAmendmentMapperInterface
    {
        return new SalesOrderAmendmentMapper();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface
     */
    public function createSalesOrderAmendmentCreateValidator(): SalesOrderAmendmentValidatorInterface
    {
        return new SalesOrderAmendmentValidator(
            $this->getSalesOrderAmendmentCreateValidatorRules(),
            $this->getSalesOrderAmendmentCreateValidationRulePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface
     */
    public function createSalesOrderAmendmentUpdateValidator(): SalesOrderAmendmentValidatorInterface
    {
        return new SalesOrderAmendmentValidator(
            $this->getSalesOrderAmendmentUpdateValidatorRules(),
            $this->getSalesOrderAmendmentUpdateValidationRulePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentQuoteValidatorInterface
     */
    public function createSalesOrderAmendmentQuoteValidator(): SalesOrderAmendmentQuoteValidatorInterface
    {
        return new SalesOrderAmendmentQuoteValidator($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\CartReorderValidatorInterface
     */
    public function createCartReorderValidator(): CartReorderValidatorInterface
    {
        return new CartReorderValidator();
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface>
     */
    public function getSalesOrderAmendmentCreateValidatorRules(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface>
     */
    public function getSalesOrderAmendmentUpdateValidatorRules(): array
    {
        return [
            $this->createSalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface
     */
    public function createSalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule(): SalesOrderAmendmentValidatorRuleInterface
    {
        return new SalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface
     */
    public function createErrorAdder(): ErrorAdderInterface
    {
        return new ErrorAdder();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilterInterface
     */
    public function createQuoteFieldsFilter(): QuoteFieldsFilterInterface
    {
        return new QuoteFieldsFilter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Hydrator\CartReorderItemHydratorInterface
     */
    public function createCartReorderItemHydrator(): CartReorderItemHydratorInterface
    {
        return new CartReorderItemHydrator();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Replacer\SalesOrderItemReplacerInterface
     */
    public function createSalesOrderItemReplacer(): SalesOrderItemReplacerInterface
    {
        return new SalesOrderItemReplacer(
            $this->createGroupKeyQuantitySalesOrderAmendmentItemCollectorStrategy(),
            $this->getSalesFacade(),
            $this->getSalesOrderAmendmentItemCollectorStrategyPlugins(),
            $this->getSalesOrderItemCollectorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Strategy\SalesOrderAmendmentItemCollectorStrategyInterface
     */
    public function createGroupKeyQuantitySalesOrderAmendmentItemCollectorStrategy(): SalesOrderAmendmentItemCollectorStrategyInterface
    {
        return new GroupKeyQuantitySalesOrderAmendmentItemCollectorStrategy();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Checker\CartCheckerInterface
     */
    public function createCartChecker(): CartCheckerInterface
    {
        return new CartChecker(
            $this->createOrderReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Validator\QuoteRequestValidatorInterface
     */
    public function createQuoteRequestValidator(): QuoteRequestValidatorInterface
    {
        return new QuoteRequestValidator();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Resolver\SalesOrderAmendmentAvailabilityResolverInterface
     */
    public function createSalesOrderAmendmentAvailabilityResolver(): SalesOrderAmendmentAvailabilityResolverInterface
    {
        return new SalesOrderAmendmentAvailabilityResolver($this->getSalesOrderAmendmentService());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentQuoteReaderInterface
     */
    public function createSalesOrderAmendmentQuoteReader(): SalesOrderAmendmentQuoteReaderInterface
    {
        return new SalesOrderAmendmentQuoteReader(
            $this->getRepository(),
            $this->getSalesOrderAmendmentQuoteExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Saver\SalesOrderAmendmentQuoteSaverInterface
     */
    public function createSalesOrderAmendmentQuoteSaver(): SalesOrderAmendmentQuoteSaverInterface
    {
        return new SalesOrderAmendmentQuoteSaver(
            $this->createSalesOrderAmendmentQuoteCreator(),
            $this->createSalesOrderAmendmentQuoteReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesOrderAmendmentToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToQuoteFacadeInterface
     */
    public function getQuoteFacade(): SalesOrderAmendmentToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentExpanderPluginInterface>
     */
    public function getSalesOrderAmendmentExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface>
     */
    public function getSalesOrderAmendmentCreateValidationRulePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_CREATE_VALIDATION_RULE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface>
     */
    public function getSalesOrderAmendmentPreCreatePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_PRE_CREATE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface>
     */
    public function getSalesOrderAmendmentPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_POST_CREATE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface>
     */
    public function getSalesOrderAmendmentUpdateValidationRulePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_UPDATE_VALIDATION_RULE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreUpdatePluginInterface>
     */
    public function getSalesOrderAmendmentPreUpdatePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_PRE_UPDATE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostUpdatePluginInterface>
     */
    public function getSalesOrderAmendmentPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_POST_UPDATE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreDeletePluginInterface>
     */
    public function getSalesOrderAmendmentPreDeletePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_PRE_DELETE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostDeletePluginInterface>
     */
    public function getSalesOrderAmendmentPostDeletePlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_POST_DELETE);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentItemCollectorStrategyPluginInterface>
     */
    public function getSalesOrderAmendmentItemCollectorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_ITEM_COLLECTOR_STRATEGY);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderItemCollectorPluginInterface>
     */
    public function getSalesOrderItemCollectorPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_ITEM_COLLECTOR_PLUGIN);
    }

    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentQuoteExpanderPluginInterface>
     */
    public function getSalesOrderAmendmentQuoteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_QUOTE_EXPANDER);
    }

    /**
     * @return \Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface
     */
    public function getSalesOrderAmendmentService(): SalesOrderAmendmentServiceInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::SERVICE_SALES_ORDER_AMENDMENT);
    }
}
