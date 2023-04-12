<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointStoreRelationCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointStoreRelationCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpander;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractor;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointFilter;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointFilterInterface;
use Spryker\Zed\ServicePoint\Business\Reader\ServicePointReader;
use Spryker\Zed\ServicePoint\Business\Reader\ServicePointReaderInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointStoreRelationUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointStoreRelationUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointExistenceByUuidServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyExistenceServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyLengthServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyUniquenessServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointNameLengthServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\StoreExistenceServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointValidator;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointValidatorInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdder;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface;
use Spryker\Zed\ServicePoint\ServicePointDependencyProvider;

/**
 * @method \Spryker\Zed\ServicePoint\ServicePointConfig getConfig()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface getRepository()
 */
class ServicePointBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ServicePoint\Business\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getRepository(),
            $this->createServicePointStoreRelationExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Creator\ServicePointCreatorInterface
     */
    public function createServicePointCreator(): ServicePointCreatorInterface
    {
        return new ServicePointCreator(
            $this->createServicePointStoreRelationCreator(),
            $this->getEntityManager(),
            $this->createServicePointCreateValidator(),
            $this->createServicePointFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Updater\ServicePointUpdaterInterface
     */
    public function createServicePointUpdater(): ServicePointUpdaterInterface
    {
        return new ServicePointUpdater(
            $this->createServicePointStoreRelationUpdater(),
            $this->getEntityManager(),
            $this->createServicePointUpdateValidator(),
            $this->createServicePointFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Creator\ServicePointStoreRelationCreatorInterface
     */
    public function createServicePointStoreRelationCreator(): ServicePointStoreRelationCreatorInterface
    {
        return new ServicePointStoreRelationCreator(
            $this->getStoreFacade(),
            $this->getEntityManager(),
            $this->createServicePointStoreExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Updater\ServicePointStoreRelationUpdaterInterface
     */
    public function createServicePointStoreRelationUpdater(): ServicePointStoreRelationUpdaterInterface
    {
        return new ServicePointStoreRelationUpdater(
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createServicePointStoreExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface
     */
    public function createServicePointStoreRelationExpander(): ServicePointStoreRelationExpanderInterface
    {
        return new ServicePointStoreRelationExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface
     */
    public function createServicePointStoreExtractor(): ServicePointStoreExtractorInterface
    {
        return new ServicePointStoreExtractor();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Filter\ServicePointFilterInterface
     */
    public function createServicePointFilter(): ServicePointFilterInterface
    {
        return new ServicePointFilter();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServicePointValidatorInterface
     */
    public function createServicePointCreateValidator(): ServicePointValidatorInterface
    {
        return new ServicePointValidator($this->getServicePointCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServicePointValidatorInterface
     */
    public function createServicePointUpdateValidator(): ServicePointValidatorInterface
    {
        return new ServicePointValidator($this->getServicePointUpdateValidatorRules());
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface>
     */
    public function getServicePointCreateValidatorRules(): array
    {
        return [
            $this->createServicePointKeyUniquenessServicePointValidatorRule(),
            $this->createServicePointKeyLengthServicePointValidatorRule(),
            $this->createServicePointNameLengthServicePointValidatorRule(),
            $this->createServicePointKeyExistenceServicePointValidatorRule(),
            $this->createStoreExistenceServicePointValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface>
     */
    public function getServicePointUpdateValidatorRules(): array
    {
        return [
            $this->createServicePointExistenceByUuidServicePointValidatorRule(),
            $this->createServicePointKeyUniquenessServicePointValidatorRule(),
            $this->createServicePointKeyLengthServicePointValidatorRule(),
            $this->createServicePointNameLengthServicePointValidatorRule(),
            $this->createServicePointKeyExistenceServicePointValidatorRule(),
            $this->createStoreExistenceServicePointValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface
     */
    public function createServicePointKeyExistenceServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new ServicePointKeyExistenceServicePointValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface
     */
    public function createServicePointNameLengthServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new ServicePointNameLengthServicePointValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface
     */
    public function createServicePointKeyLengthServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new ServicePointKeyLengthServicePointValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface
     */
    public function createServicePointKeyUniquenessServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new ServicePointKeyUniquenessServicePointValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface
     */
    public function createStoreExistenceServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new StoreExistenceServicePointValidatorRule(
            $this->getStoreFacade(),
            $this->createErrorAdder(),
            $this->createServicePointStoreExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointValidatorRuleInterface
     */
    public function createServicePointExistenceByUuidServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new ServicePointExistenceByUuidServicePointValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    public function createErrorAdder(): ErrorAdderInterface
    {
        return new ErrorAdder();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface
     */
    public function getStoreFacade(): ServicePointToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointDependencyProvider::FACADE_STORE);
    }
}
