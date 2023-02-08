<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WarehouseUser\Business\Creator\WarehouseUserAssignmentCreator;
use Spryker\Zed\WarehouseUser\Business\Creator\WarehouseUserAssignmentCreatorInterface;
use Spryker\Zed\WarehouseUser\Business\Deleter\WarehouseUserAssignmentDeleter;
use Spryker\Zed\WarehouseUser\Business\Deleter\WarehouseUserAssignmentDeleterInterface;
use Spryker\Zed\WarehouseUser\Business\Expander\WarehouseUserAssignmentExpander;
use Spryker\Zed\WarehouseUser\Business\Expander\WarehouseUserAssignmentExpanderInterface;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilder;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface;
use Spryker\Zed\WarehouseUser\Business\Mapper\WarehouseUserAssignmentCriteriaMapper;
use Spryker\Zed\WarehouseUser\Business\Mapper\WarehouseUserAssignmentCriteriaMapperInterface;
use Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentStatusUpdater;
use Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentStatusUpdaterInterface;
use Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentUpdater;
use Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentUpdaterInterface;
use Spryker\Zed\WarehouseUser\Business\Validator\Rules\SingleActiveWarehouseUserAssignmentValidatorRule;
use Spryker\Zed\WarehouseUser\Business\Validator\Rules\UserExistsValidatorRule;
use Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseExistsValidatorRule;
use Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentAlreadyExistsValidatorRule;
use Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentExistsValidatorRule;
use Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface;
use Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidator;
use Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidatorInterface;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToUserFacadeInterface;
use Spryker\Zed\WarehouseUser\WarehouseUserDependencyProvider;

/**
 * @method \Spryker\Zed\WarehouseUser\WarehouseUserConfig getConfig()
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface getRepository()
 */
class WarehouseUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Creator\WarehouseUserAssignmentCreatorInterface
     */
    public function createWarehouseUserAssignmentCreator(): WarehouseUserAssignmentCreatorInterface
    {
        return new WarehouseUserAssignmentCreator(
            $this->getEntityManager(),
            $this->createWarehouseUserAssignmentExpander(),
            $this->createWarehouseUserAssignmentCreateValidator(),
            $this->createWarehouseUserAssignmentStatusUpdater(),
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentUpdaterInterface
     */
    public function createWarehouseUserAssignmentUpdater(): WarehouseUserAssignmentUpdaterInterface
    {
        return new WarehouseUserAssignmentUpdater(
            $this->getEntityManager(),
            $this->createWarehouseUserAssignmentUpdateValidator(),
            $this->createWarehouseUserAssignmentStatusUpdater(),
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentStatusUpdaterInterface
     */
    public function createWarehouseUserAssignmentStatusUpdater(): WarehouseUserAssignmentStatusUpdaterInterface
    {
        return new WarehouseUserAssignmentStatusUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Deleter\WarehouseUserAssignmentDeleterInterface
     */
    public function createWarehouseUserAssignmentDeleter(): WarehouseUserAssignmentDeleterInterface
    {
        return new WarehouseUserAssignmentDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createWarehouseUserAssignmentCriteriaMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface
     */
    public function createWarehouseUserAssignmentIdentifierBuilder(): WarehouseUserAssignmentIdentifierBuilderInterface
    {
        return new WarehouseUserAssignmentIdentifierBuilder();
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Expander\WarehouseUserAssignmentExpanderInterface
     */
    public function createWarehouseUserAssignmentExpander(): WarehouseUserAssignmentExpanderInterface
    {
        return new WarehouseUserAssignmentExpander($this->getStockFacade());
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Mapper\WarehouseUserAssignmentCriteriaMapperInterface
     */
    public function createWarehouseUserAssignmentCriteriaMapper(): WarehouseUserAssignmentCriteriaMapperInterface
    {
        return new WarehouseUserAssignmentCriteriaMapper();
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidatorInterface
     */
    public function createWarehouseUserAssignmentCreateValidator(): WarehouseUserAssignmentValidatorInterface
    {
        return new WarehouseUserAssignmentValidator($this->getWarehouseUserAssignmentCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidatorInterface
     */
    public function createWarehouseUserAssignmentUpdateValidator(): WarehouseUserAssignmentValidatorInterface
    {
        return new WarehouseUserAssignmentValidator($this->getWarehouseUserAssignmentUpdateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface
     */
    public function createUserExistsValidatorRule(): WarehouseUserAssignmentValidatorRuleInterface
    {
        return new UserExistsValidatorRule(
            $this->getUserFacade(),
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface
     */
    public function createWarehouseExistsValidatorRule(): WarehouseUserAssignmentValidatorRuleInterface
    {
        return new WarehouseExistsValidatorRule(
            $this->getStockFacade(),
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface
     */
    public function createSingleActiveWarehouseUserAssignmentValidatorRule(): WarehouseUserAssignmentValidatorRuleInterface
    {
        return new SingleActiveWarehouseUserAssignmentValidatorRule(
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface
     */
    public function createWarehouseUserAssignmentExistsValidatorRule(): WarehouseUserAssignmentValidatorRuleInterface
    {
        return new WarehouseUserAssignmentExistsValidatorRule(
            $this->getRepository(),
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface
     */
    public function createWarehouseUserAssignmentAlreadyExistsValidatorRule(): WarehouseUserAssignmentValidatorRuleInterface
    {
        return new WarehouseUserAssignmentAlreadyExistsValidatorRule(
            $this->getRepository(),
            $this->createWarehouseUserAssignmentIdentifierBuilder(),
        );
    }

    /**
     * @return list<\Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface>
     */
    public function getWarehouseUserAssignmentCreateValidatorRules(): array
    {
        return [
            $this->createUserExistsValidatorRule(),
            $this->createWarehouseExistsValidatorRule(),
            $this->createSingleActiveWarehouseUserAssignmentValidatorRule(),
            $this->createWarehouseUserAssignmentAlreadyExistsValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface>
     */
    public function getWarehouseUserAssignmentUpdateValidatorRules(): array
    {
        return [
            $this->createWarehouseUserAssignmentExistsValidatorRule(),
            $this->createUserExistsValidatorRule(),
            $this->createWarehouseExistsValidatorRule(),
            $this->createSingleActiveWarehouseUserAssignmentValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToUserFacadeInterface
     */
    public function getUserFacade(): WarehouseUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseUserDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface
     */
    public function getStockFacade(): WarehouseUserToStockFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseUserDependencyProvider::FACADE_STOCK);
    }
}
