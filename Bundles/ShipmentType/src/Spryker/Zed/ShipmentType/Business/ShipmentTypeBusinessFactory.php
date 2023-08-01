<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeCreator;
use Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeCreatorInterface;
use Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeStoreRelationCreator;
use Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeStoreRelationCreatorInterface;
use Spryker\Zed\ShipmentType\Business\Expander\ShipmentMethodCollectionExpander;
use Spryker\Zed\ShipmentType\Business\Expander\ShipmentMethodCollectionExpanderInterface;
use Spryker\Zed\ShipmentType\Business\Expander\ShipmentTypeStoreRelationshipExpander;
use Spryker\Zed\ShipmentType\Business\Expander\ShipmentTypeStoreRelationshipExpanderInterface;
use Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractor;
use Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\ShipmentType\Business\Filter\ShipmentGroupFilter;
use Spryker\Zed\ShipmentType\Business\Filter\ShipmentGroupFilterInterface;
use Spryker\Zed\ShipmentType\Business\Grouper\ShipmentTypeGrouper;
use Spryker\Zed\ShipmentType\Business\Grouper\ShipmentTypeGrouperInterface;
use Spryker\Zed\ShipmentType\Business\Reader\ShipmentTypeReader;
use Spryker\Zed\ShipmentType\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentType\Business\Updater\ShipmentTypeStoreRelationUpdater;
use Spryker\Zed\ShipmentType\Business\Updater\ShipmentTypeStoreRelationUpdaterInterface;
use Spryker\Zed\ShipmentType\Business\Updater\ShipmentTypeUpdater;
use Spryker\Zed\ShipmentType\Business\Updater\ShipmentTypeUpdaterInterface;
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreator;
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeExistsShipmentTypeValidatorRule;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeKeyExistsShipmentTypeValidatorRule;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeKeyLengthShipmentTypeValidatorRule;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeKeyUniqueShipmentTypeValidatorRule;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeNameLengthShipmentTypeValidatorRule;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface;
use Spryker\Zed\ShipmentType\Business\Validator\Rule\StoreExistsShipmentTypeValidatorRule;
use Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidator;
use Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidatorInterface;
use Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface;
use Spryker\Zed\ShipmentType\ShipmentTypeDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentType\ShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface getRepository()
 */
class ShipmentTypeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getRepository(),
            $this->createShipmentTypeStoreRelationshipExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Expander\ShipmentTypeStoreRelationshipExpanderInterface
     */
    public function createShipmentTypeStoreRelationshipExpander(): ShipmentTypeStoreRelationshipExpanderInterface
    {
        return new ShipmentTypeStoreRelationshipExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeCreatorInterface
     */
    public function createShipmentTypeCreator(): ShipmentTypeCreatorInterface
    {
        return new ShipmentTypeCreator(
            $this->getEntityManager(),
            $this->createShipmentTypeCreateValidator(),
            $this->createShipmentTypeStoreRelationCreator(),
            $this->createShipmentTypeGrouper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeStoreRelationCreatorInterface
     */
    public function createShipmentTypeStoreRelationCreator(): ShipmentTypeStoreRelationCreatorInterface
    {
        return new ShipmentTypeStoreRelationCreator(
            $this->getEntityManager(),
            $this->getStoreFacade(),
            $this->createStoreDataExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Updater\ShipmentTypeUpdaterInterface
     */
    public function createShipmentTypeUpdater(): ShipmentTypeUpdaterInterface
    {
        return new ShipmentTypeUpdater(
            $this->getEntityManager(),
            $this->createShipmentTypeUpdateValidator(),
            $this->createShipmentTypeStoreRelationUpdater(),
            $this->createShipmentTypeGrouper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Updater\ShipmentTypeStoreRelationUpdaterInterface
     */
    public function createShipmentTypeStoreRelationUpdater(): ShipmentTypeStoreRelationUpdaterInterface
    {
        return new ShipmentTypeStoreRelationUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreFacade(),
            $this->createStoreDataExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Expander\ShipmentMethodCollectionExpanderInterface
     */
    public function createShipmentMethodCollectionExpander(): ShipmentMethodCollectionExpanderInterface
    {
        return new ShipmentMethodCollectionExpander(
            $this->createShipmentTypeReader(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidatorInterface
     */
    public function createShipmentTypeCreateValidator(): ShipmentTypeValidatorInterface
    {
        return new ShipmentTypeValidator($this->getShipmentTypeCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidatorInterface
     */
    public function createShipmentTypeUpdateValidator(): ShipmentTypeValidatorInterface
    {
        return new ShipmentTypeValidator($this->getShipmentTypeUpdateValidatorRules());
    }

    /**
     * @return array<\Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface>
     */
    public function getShipmentTypeCreateValidatorRules(): array
    {
        return [
            $this->createShipmentTypeKeyUniqueShipmentTypeValidatorRule(),
            $this->createShipmentTypeKeyLengthShipmentTypeValidatorRule(),
            $this->createShipmentTypeKeyExistsShipmentTypeValidatorRule(),
            $this->createShipmentTypeNameLengthShipmentTypeValidatorRule(),
            $this->createStoreExistsShipmentTypeValidatorRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface>
     */
    public function getShipmentTypeUpdateValidatorRules(): array
    {
        return [
            $this->createShipmentTypeExistsShipmentTypeValidatorRule(),
            $this->createShipmentTypeKeyUniqueShipmentTypeValidatorRule(),
            $this->createShipmentTypeKeyLengthShipmentTypeValidatorRule(),
            $this->createShipmentTypeKeyExistsShipmentTypeValidatorRule(),
            $this->createShipmentTypeNameLengthShipmentTypeValidatorRule(),
            $this->createStoreExistsShipmentTypeValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface
     */
    public function createShipmentTypeKeyExistsShipmentTypeValidatorRule(): ShipmentTypeValidatorRuleInterface
    {
        return new ShipmentTypeKeyExistsShipmentTypeValidatorRule(
            $this->getRepository(),
            $this->createValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface
     */
    public function createShipmentTypeKeyLengthShipmentTypeValidatorRule(): ShipmentTypeValidatorRuleInterface
    {
        return new ShipmentTypeKeyLengthShipmentTypeValidatorRule($this->createValidationErrorCreator());
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface
     */
    public function createShipmentTypeKeyUniqueShipmentTypeValidatorRule(): ShipmentTypeValidatorRuleInterface
    {
        return new ShipmentTypeKeyUniqueShipmentTypeValidatorRule($this->createValidationErrorCreator());
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface
     */
    public function createShipmentTypeNameLengthShipmentTypeValidatorRule(): ShipmentTypeValidatorRuleInterface
    {
        return new ShipmentTypeNameLengthShipmentTypeValidatorRule($this->createValidationErrorCreator());
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface
     */
    public function createStoreExistsShipmentTypeValidatorRule(): ShipmentTypeValidatorRuleInterface
    {
        return new StoreExistsShipmentTypeValidatorRule(
            $this->getStoreFacade(),
            $this->createStoreDataExtractor(),
            $this->createValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface
     */
    public function createShipmentTypeExistsShipmentTypeValidatorRule(): ShipmentTypeValidatorRuleInterface
    {
        return new ShipmentTypeExistsShipmentTypeValidatorRule(
            $this->getRepository(),
            $this->createValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface
     */
    public function createValidationErrorCreator(): ValidationErrorCreatorInterface
    {
        return new ValidationErrorCreator();
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Grouper\ShipmentTypeGrouperInterface
     */
    public function createShipmentTypeGrouper(): ShipmentTypeGrouperInterface
    {
        return new ShipmentTypeGrouper();
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface
     */
    public function createStoreDataExtractor(): StoreDataExtractorInterface
    {
        return new StoreDataExtractor();
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\Filter\ShipmentGroupFilterInterface
     */
    public function createShipmentGroupFilter(): ShipmentGroupFilterInterface
    {
        return new ShipmentGroupFilter($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface
     */
    public function getStoreFacade(): ShipmentTypeToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeDependencyProvider::FACADE_STORE);
    }
}
