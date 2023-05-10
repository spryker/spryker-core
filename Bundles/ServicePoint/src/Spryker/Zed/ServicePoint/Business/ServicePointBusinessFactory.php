<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointAddressCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointAddressCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointServiceCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointServiceCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointStoreRelationCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServicePointStoreRelationCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Creator\ServiceTypeCreator;
use Spryker\Zed\ServicePoint\Business\Creator\ServiceTypeCreatorInterface;
use Spryker\Zed\ServicePoint\Business\Expander\CountryExpander;
use Spryker\Zed\ServicePoint\Business\Expander\CountryExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointExpander;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServicePointExpander;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServicePointExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServiceTypeExpander;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServiceTypeExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpander;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractor;
use Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractor;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointAddressFilter;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointAddressFilterInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointFilter;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointFilterInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointServiceFilter;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointServiceFilterInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServiceTypeFilter;
use Spryker\Zed\ServicePoint\Business\Filter\ServiceTypeFilterInterface;
use Spryker\Zed\ServicePoint\Business\Reader\ServicePointReader;
use Spryker\Zed\ServicePoint\Business\Reader\ServicePointReaderInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointAddressUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointAddressUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointServiceUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointServiceUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointStoreRelationUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointStoreRelationUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServicePointUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Updater\ServiceTypeUpdater;
use Spryker\Zed\ServicePoint\Business\Updater\ServiceTypeUpdaterInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ExistenceByUuidServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\KeyExistenceServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\KeyLengthServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\KeyUniquenessServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\NameLengthServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\StoreExistenceServicePointValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\AddressLengthServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\CityLengthServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\CountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ExistenceByUuidServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointExistenceByUuidServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointHasSingleServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointUuidUniquenessServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ZipCodeLengthServicePointAddressValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyExistenceServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyImmutabilityServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyLengthServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\KeyUniquenessServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceTypeExistenceServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceTypeUniquenessServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointUuidExistenceServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServiceTypeUuidExistenceServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\UuidExistenceServicePointServiceValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyExistenceServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyImmutabilityServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyLengthServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\KeyUniquenessServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\NameExistenceServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\NameLengthServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\NameUniquenessServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\UuidExistenceServiceTypeValidatorRule;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidator;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidatorInterface;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidator;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidatorInterface;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointValidator;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointValidatorInterface;
use Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidator;
use Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidatorInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdder;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface;
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
     * @return \Spryker\Zed\ServicePoint\Business\Creator\ServicePointAddressCreatorInterface
     */
    public function createServicePointAddressCreator(): ServicePointAddressCreatorInterface
    {
        return new ServicePointAddressCreator(
            $this->getEntityManager(),
            $this->createServicePointAddressFilter(),
            $this->createServicePointAddressCreateValidator(),
            $this->createCountryExpander(),
            $this->createServicePointExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Updater\ServicePointAddressUpdaterInterface
     */
    public function createServicePointAddressUpdater(): ServicePointAddressUpdaterInterface
    {
        return new ServicePointAddressUpdater(
            $this->getEntityManager(),
            $this->createServicePointAddressFilter(),
            $this->createServicePointAddressUpdateValidator(),
            $this->createCountryExpander(),
            $this->createServicePointExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Expander\CountryExpanderInterface
     */
    public function createCountryExpander(): CountryExpanderInterface
    {
        return new CountryExpander(
            $this->getCountryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Expander\ServicePointExpanderInterface
     */
    public function createServicePointExpander(): ServicePointExpanderInterface
    {
        return new ServicePointExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface
     */
    public function createErrorExtractor(): ErrorExtractorInterface
    {
        return new ErrorExtractor();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Filter\ServicePointAddressFilterInterface
     */
    public function createServicePointAddressFilter(): ServicePointAddressFilterInterface
    {
        return new ServicePointAddressFilter(
            $this->createErrorExtractor(),
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
        return new ServicePointFilter(
            $this->createErrorExtractor(),
        );
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
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidatorInterface
     */
    public function createServicePointAddressCreateValidator(): ServicePointAddressValidatorInterface
    {
        return new ServicePointAddressValidator($this->getServicePointAddressCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidatorInterface
     */
    public function createServicePointAddressUpdateValidator(): ServicePointAddressValidatorInterface
    {
        return new ServicePointAddressValidator($this->getServicePointAddressUpdateValidatorRules());
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface>
     */
    public function getServicePointAddressCreateValidatorRules(): array
    {
        return [
            $this->createServicePointExistenceByUuidServicePointAddressValidatorRule(),
            $this->createCountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule(),
            $this->createAddressLengthServicePointAddressValidatorRule(),
            $this->createCityLengthServicePointAddressValidatorRule(),
            $this->createZipCodeLengthServicePointAddressValidatorRule(),
            $this->createServicePointHasSingleServicePointAddressValidatorRule(),
            $this->createServicePointUuidUniquenessServicePointAddressValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface>
     */
    public function getServicePointAddressUpdateValidatorRules(): array
    {
        return [
            $this->createExistenceByUuidServicePointAddressValidatorRule(),
            $this->createServicePointExistenceByUuidServicePointAddressValidatorRule(),
            $this->createCountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule(),
            $this->createAddressLengthServicePointAddressValidatorRule(),
            $this->createCityLengthServicePointAddressValidatorRule(),
            $this->createZipCodeLengthServicePointAddressValidatorRule(),
            $this->createServicePointHasSingleServicePointAddressValidatorRule(),
            $this->createServicePointUuidUniquenessServicePointAddressValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface>
     */
    public function getServicePointCreateValidatorRules(): array
    {
        return [
            $this->createKeyUniquenessServicePointValidatorRule(),
            $this->createKeyLengthServicePointValidatorRule(),
            $this->createNameLengthServicePointValidatorRule(),
            $this->createKeyExistenceServicePointValidatorRule(),
            $this->createStoreExistenceServicePointValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface>
     */
    public function getServicePointUpdateValidatorRules(): array
    {
        return [
            $this->createExistenceByUuidServicePointValidatorRule(),
            $this->createKeyUniquenessServicePointValidatorRule(),
            $this->createKeyLengthServicePointValidatorRule(),
            $this->createNameLengthServicePointValidatorRule(),
            $this->createKeyExistenceServicePointValidatorRule(),
            $this->createStoreExistenceServicePointValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface
     */
    public function createKeyExistenceServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new KeyExistenceServicePointValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface
     */
    public function createNameLengthServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new NameLengthServicePointValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface
     */
    public function createKeyLengthServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new KeyLengthServicePointValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface
     */
    public function createKeyUniquenessServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new KeyUniquenessServicePointValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface
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
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ServicePointValidatorRuleInterface
     */
    public function createExistenceByUuidServicePointValidatorRule(): ServicePointValidatorRuleInterface
    {
        return new ExistenceByUuidServicePointValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createCountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new CountryAndRegionExistenceByIso2CodeServicePointAddressValidatorRule(
            $this->getCountryFacade(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createAddressLengthServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new AddressLengthServicePointAddressValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createCityLengthServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new CityLengthServicePointAddressValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createExistenceByUuidServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new ExistenceByUuidServicePointAddressValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createServicePointExistenceByUuidServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new ServicePointExistenceByUuidServicePointAddressValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createServicePointHasSingleServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new ServicePointHasSingleServicePointAddressValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createServicePointUuidUniquenessServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new ServicePointUuidUniquenessServicePointAddressValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface
     */
    public function createZipCodeLengthServicePointAddressValidatorRule(): ServicePointAddressValidatorRuleInterface
    {
        return new ZipCodeLengthServicePointAddressValidatorRule(
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

    /**
     * @return \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToCountryFacadeInterface
     */
    public function getCountryFacade(): ServicePointToCountryFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Creator\ServicePointServiceCreatorInterface
     */
    public function createServicePointServiceCreator(): ServicePointServiceCreatorInterface
    {
        return new ServicePointServiceCreator(
            $this->getEntityManager(),
            $this->createServicePointServiceCreateValidator(),
            $this->createServicePointServiceFilter(),
            $this->createServicePointServiceServicePointExpander(),
            $this->createServicePointServiceServiceTypeExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServicePointExpanderInterface
     */
    public function createServicePointServiceServicePointExpander(): ServicePointServiceServicePointExpanderInterface
    {
        return new ServicePointServiceServicePointExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServiceTypeExpanderInterface
     */
    public function createServicePointServiceServiceTypeExpander(): ServicePointServiceServiceTypeExpanderInterface
    {
        return new ServicePointServiceServiceTypeExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Updater\ServicePointServiceUpdaterInterface
     */
    public function createServicePointServiceUpdater(): ServicePointServiceUpdaterInterface
    {
        return new ServicePointServiceUpdater(
            $this->getEntityManager(),
            $this->createServicePointServiceUpdateValidator(),
            $this->createServicePointServiceFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidatorInterface
     */
    public function createServicePointServiceCreateValidator(): ServicePointServiceValidatorInterface
    {
        return new ServicePointServiceValidator($this->getServicePointServiceCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidatorInterface
     */
    public function createServicePointServiceUpdateValidator(): ServicePointServiceValidatorInterface
    {
        return new ServicePointServiceValidator($this->getServicePointServiceUpdateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Filter\ServicePointServiceFilterInterface
     */
    public function createServicePointServiceFilter(): ServicePointServiceFilterInterface
    {
        return new ServicePointServiceFilter(
            $this->createErrorExtractor(),
        );
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface>
     */
    public function getServicePointServiceCreateValidatorRules(): array
    {
        return [
            $this->createServicePointUuidExistenceServicePointServiceValidatorRule(),
            $this->createServiceTypeUuidExistenceServicePointServiceValidatorRule(),
            $this->createServicePointServiceTypeUniquenessServicePointServiceValidatorRule(),
            $this->createServicePointServiceTypeExistenceServicePointServiceValidatorRule(),
            $this->createKeyUniquenessServicePointServiceValidatorRule(),
            $this->createKeyLengthServicePointServiceValidatorRule(),
            $this->createKeyExistenceServicePointServiceValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface>
     */
    public function getServicePointServiceUpdateValidatorRules(): array
    {
        return [
            $this->createUuidExistenceServicePointServiceValidatorRule(),
            $this->createKeyImmutabilityServicePointServiceValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createUuidExistenceServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new UuidExistenceServicePointServiceValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createServicePointUuidExistenceServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new ServicePointUuidExistenceServicePointServiceValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createServiceTypeUuidExistenceServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new ServiceTypeUuidExistenceServicePointServiceValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createServicePointServiceTypeUniquenessServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new ServicePointServiceTypeUniquenessServicePointServiceValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createServicePointServiceTypeExistenceServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new ServicePointServiceTypeExistenceServicePointServiceValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createKeyImmutabilityServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new KeyImmutabilityServicePointServiceValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createKeyUniquenessServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new KeyUniquenessServicePointServiceValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createKeyLengthServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new KeyLengthServicePointServiceValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface
     */
    public function createKeyExistenceServicePointServiceValidatorRule(): ServicePointServiceValidatorRuleInterface
    {
        return new KeyExistenceServicePointServiceValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Creator\ServiceTypeCreatorInterface
     */
    public function createServiceTypeCreator(): ServiceTypeCreatorInterface
    {
        return new ServiceTypeCreator(
            $this->getEntityManager(),
            $this->createServiceTypeCreateValidator(),
            $this->createServiceTypeFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Updater\ServiceTypeUpdaterInterface
     */
    public function createServiceTypeUpdater(): ServiceTypeUpdaterInterface
    {
        return new ServiceTypeUpdater(
            $this->getEntityManager(),
            $this->createServiceTypeUpdateValidator(),
            $this->createServiceTypeFilter(),
        );
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface>
     */
    public function getServiceTypeCreateValidatorRules(): array
    {
        return [
            $this->createNameUniquenessServiceTypeValidatorRule(),
            $this->createNameLengthServiceTypeValidatorRule(),
            $this->createNameExistenceServiceTypeValidatorRule(),
            $this->createKeyUniquenessServiceTypeValidatorRule(),
            $this->createKeyLengthServiceTypeValidatorRule(),
            $this->createKeyExistenceServiceTypeValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface>
     */
    public function getServiceTypeUpdateValidatorRules(): array
    {
        return [
            $this->createUuidExistenceServiceTypeValidatorRule(),
            $this->createNameUniquenessServiceTypeValidatorRule(),
            $this->createNameLengthServiceTypeValidatorRule(),
            $this->createNameExistenceServiceTypeValidatorRule(),
            $this->createKeyImmutabilityServiceTypeValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidatorInterface
     */
    public function createServiceTypeCreateValidator(): ServiceTypeValidatorInterface
    {
        return new ServiceTypeValidator($this->getServiceTypeCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidatorInterface
     */
    public function createServiceTypeUpdateValidator(): ServiceTypeValidatorInterface
    {
        return new ServiceTypeValidator($this->getServiceTypeUpdateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Filter\ServiceTypeFilterInterface
     */
    public function createServiceTypeFilter(): ServiceTypeFilterInterface
    {
        return new ServiceTypeFilter(
            $this->createErrorExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createUuidExistenceServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new UuidExistenceServiceTypeValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createNameLengthServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new NameLengthServiceTypeValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createNameUniquenessServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new NameUniquenessServiceTypeValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createNameExistenceServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new NameExistenceServiceTypeValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createKeyImmutabilityServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new KeyImmutabilityServiceTypeValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createKeyUniquenessServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new KeyUniquenessServiceTypeValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createKeyLengthServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new KeyLengthServiceTypeValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface
     */
    public function createKeyExistenceServiceTypeValidatorRule(): ServiceTypeValidatorRuleInterface
    {
        return new KeyExistenceServiceTypeValidatorRule(
            $this->getRepository(),
            $this->createErrorAdder(),
        );
    }
}
