<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationship\Business\Builder\MerchantRelationshipDeleteMailBuilder;
use Spryker\Zed\MerchantRelationship\Business\Builder\MerchantRelationshipDeleteMailBuilderInterface;
use Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCompanyBusinessUnitCreator;
use Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCompanyBusinessUnitCreatorInterface;
use Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCreator;
use Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCreatorInterface;
use Spryker\Zed\MerchantRelationship\Business\Deleter\MerchantRelationshipDeleter;
use Spryker\Zed\MerchantRelationship\Business\Deleter\MerchantRelationshipDeleterInterface;
use Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpander;
use Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface;
use Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGenerator;
use Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCompanyBusinessUnitMapper;
use Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCompanyBusinessUnitMapperInterface;
use Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCriteriaMapper;
use Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCriteriaMapperInterface;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReader;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationship\Business\Reader\CompanyBusinessUnitReader;
use Spryker\Zed\MerchantRelationship\Business\Reader\CompanyBusinessUnitReaderInterface;
use Spryker\Zed\MerchantRelationship\Business\Reader\MerchantReader;
use Spryker\Zed\MerchantRelationship\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantRelationship\Business\Sender\MerchantRelationshipDeleteMailNotificationSender;
use Spryker\Zed\MerchantRelationship\Business\Sender\MerchantRelationshipDeleteMailNotificationSenderInterface;
use Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipCompanyBusinessUnitUpdater;
use Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipCompanyBusinessUnitUpdaterInterface;
use Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipUpdater;
use Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipUpdaterInterface;
use Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipCreateValidator;
use Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipUpdateValidator;
use Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\AssignedCompanyBusinessUnitAllowedCreateValidatorRule;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\AssignedCompanyBusinessUnitAllowedUpdateValidatorRule;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantReferenceExistsValidatorRule;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipKeyUniqueValidatorRule;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\OwnerCompanyBusinessUnitAllowedValidatorRule;
use Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\OwnerCompanyBusinessUnitExistsValidatorRule;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToLocaleFacadeInterface;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface;
use Spryker\Zed\MerchantRelationship\MerchantRelationshipDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCreatorInterface
     */
    public function createMerchantRelationshipCreator(): MerchantRelationshipCreatorInterface
    {
        return new MerchantRelationshipCreator(
            $this->getEntityManager(),
            $this->createMerchantRelationshipCreateValidator(),
            $this->createMerchantRelationshipKeyGenerator(),
            $this->createMerchantRelationshipCompanyBusinessUnitCreator(),
            $this->getMerchantFacade(),
            $this->getMerchantRelationshipPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCompanyBusinessUnitCreatorInterface
     */
    public function createMerchantRelationshipCompanyBusinessUnitCreator(): MerchantRelationshipCompanyBusinessUnitCreatorInterface
    {
        return new MerchantRelationshipCompanyBusinessUnitCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipUpdaterInterface
     */
    public function createMerchantRelationshipUpdater(): MerchantRelationshipUpdaterInterface
    {
        return new MerchantRelationshipUpdater(
            $this->getEntityManager(),
            $this->createMerchantRelationshipUpdateValidator(),
            $this->createMerchantRelationshipKeyGenerator(),
            $this->createMerchantRelationshipCompanyBusinessUnitUpdater(),
            $this->getMerchantRelationshipPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Deleter\MerchantRelationshipDeleterInterface
     */
    public function createMerchantRelationshipDeleter(): MerchantRelationshipDeleterInterface
    {
        return new MerchantRelationshipDeleter(
            $this->getEntityManager(),
            $this->createMerchantRelationshipReader(),
            $this->getMerchantRelationshipPreDeletePlugins(),
            $this->getMerchantRelationshipPostDeletePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipCompanyBusinessUnitUpdaterInterface
     */
    public function createMerchantRelationshipCompanyBusinessUnitUpdater(): MerchantRelationshipCompanyBusinessUnitUpdaterInterface
    {
        return new MerchantRelationshipCompanyBusinessUnitUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createMerchantRelationshipCompanyBusinessUnitMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCompanyBusinessUnitMapperInterface
     */
    public function createMerchantRelationshipCompanyBusinessUnitMapper(): MerchantRelationshipCompanyBusinessUnitMapperInterface
    {
        return new MerchantRelationshipCompanyBusinessUnitMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getRepository(),
            $this->createMerchantRelationshipExpander(),
            $this->createMerchantRelationshipCriteriaMapper(),
            $this->getMerchantRelationshipExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCriteriaMapperInterface
     */
    public function createMerchantRelationshipCriteriaMapper(): MerchantRelationshipCriteriaMapperInterface
    {
        return new MerchantRelationshipCriteriaMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface
     */
    public function createMerchantRelationshipKeyGenerator(): MerchantRelationshipKeyGeneratorInterface
    {
        return new MerchantRelationshipKeyGenerator($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface
     */
    public function createMerchantRelationshipExpander(): MerchantRelationshipExpanderInterface
    {
        return new MerchantRelationshipExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface
     */
    public function createMerchantRelationshipCreateValidator(): MerchantRelationshipValidatorInterface
    {
        return new MerchantRelationshipCreateValidator(
            [
                $this->createMerchantRelationshipKeyUniqueValidatorRule(),
                $this->createMerchantReferenceExistsValidatorRule(),
                $this->createOwnerCompanyBusinessUnitExistsValidatorRule(),
                $this->createAssignedCompanyBusinessUnitAllowedCreateValidatorRule(),
            ],
            $this->getMerchantRelationshipCreateValidatorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface
     */
    public function createMerchantRelationshipUpdateValidator(): MerchantRelationshipValidatorInterface
    {
        return new MerchantRelationshipUpdateValidator(
            [
                $this->createOwnerCompanyBusinessUnitAllowedValidatorRule(),
                $this->createAssignedCompanyBusinessUnitAllowedUpdateValidatorRule(),
            ],
            $this->getMerchantRelationshipUpdateValidatorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface
     */
    public function createMerchantReferenceExistsValidatorRule(): MerchantRelationshipValidatorRuleInterface
    {
        return new MerchantReferenceExistsValidatorRule(
            $this->getMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface
     */
    public function createMerchantRelationshipKeyUniqueValidatorRule(): MerchantRelationshipValidatorRuleInterface
    {
        return new MerchantRelationshipKeyUniqueValidatorRule($this->createMerchantRelationshipReader());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface
     */
    public function createOwnerCompanyBusinessUnitExistsValidatorRule(): MerchantRelationshipValidatorRuleInterface
    {
        return new OwnerCompanyBusinessUnitExistsValidatorRule(
            $this->getCompanyBusinessUnitFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface
     */
    public function createOwnerCompanyBusinessUnitAllowedValidatorRule(): MerchantRelationshipValidatorRuleInterface
    {
        return new OwnerCompanyBusinessUnitAllowedValidatorRule(
            $this->getRepository(),
            $this->getCompanyBusinessUnitFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface
     */
    public function createAssignedCompanyBusinessUnitAllowedUpdateValidatorRule(): MerchantRelationshipValidatorRuleInterface
    {
        return new AssignedCompanyBusinessUnitAllowedUpdateValidatorRule(
            $this->getRepository(),
            $this->getCompanyBusinessUnitFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule\MerchantRelationshipValidatorRuleInterface
     */
    public function createAssignedCompanyBusinessUnitAllowedCreateValidatorRule(): MerchantRelationshipValidatorRuleInterface
    {
        return new AssignedCompanyBusinessUnitAllowedCreateValidatorRule(
            $this->getRepository(),
            $this->getCompanyBusinessUnitFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Sender\MerchantRelationshipDeleteMailNotificationSenderInterface
     */
    public function createMerchantRelationshipDeleteMailNotificationSender(): MerchantRelationshipDeleteMailNotificationSenderInterface
    {
        return new MerchantRelationshipDeleteMailNotificationSender(
            $this->createCompanyBusinessUnitReader(),
            $this->createMerchantReader(),
            $this->createMerchantRelationshipDeleteMailBuilder(),
            $this->getMailFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Builder\MerchantRelationshipDeleteMailBuilderInterface
     */
    public function createMerchantRelationshipDeleteMailBuilder(): MerchantRelationshipDeleteMailBuilderInterface
    {
        return new MerchantRelationshipDeleteMailBuilder(
            $this->getConfig(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader($this->getMerchantFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Reader\CompanyBusinessUnitReaderInterface
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader($this->getCompanyBusinessUnitFacade());
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface>
     */
    public function getMerchantRelationshipPreDeletePlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface>
     */
    public function getMerchantRelationshipPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface>
     */
    public function getMerchantRelationshipPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipCreateValidatorPluginInterface>
     */
    public function getMerchantRelationshipCreateValidatorPlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_CREATE_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipUpdateValidatorPluginInterface>
     */
    public function getMerchantRelationshipUpdateValidatorPlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_UPDATE_VALIDATOR);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantRelationshipToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): MerchantRelationshipToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface
     */
    public function getMailFacade(): MerchantRelationshipToMailFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantRelationshipToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface>
     */
    public function getMerchantRelationshipExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostDeletePluginInterface>
     */
    public function getMerchantRelationshipPostDeletePlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_POST_DELETE);
    }
}
