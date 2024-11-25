<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentCreator;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentCreatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentDeleter;
use Spryker\Zed\SalesOrderAmendment\Business\Deleter\SalesOrderAmendmentDeleterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\OrderExpander;
use Spryker\Zed\SalesOrderAmendment\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapper;
use Spryker\Zed\SalesOrderAmendment\Business\Mapper\SalesOrderAmendmentMapperInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReader;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentReaderInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentUpdater;
use Spryker\Zed\SalesOrderAmendment\Business\Updater\SalesOrderAmendmentUpdaterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\CartReorderValidator;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\CartReorderValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentExistsSalesOrderAmendmentValidatorRule;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Rules\SalesOrderAmendment\SalesOrderAmendmentValidatorRuleInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidator;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdder;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\Util\ErrorAdderInterface;
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
     * @return \Spryker\Zed\SalesOrderAmendment\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->createSalesOrderAmendmentReader());
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
}
