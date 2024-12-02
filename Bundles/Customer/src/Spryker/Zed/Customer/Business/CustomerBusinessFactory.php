<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Service\Customer\CustomerServiceInterface;
use Spryker\Zed\Customer\Business\Anonymizer\CustomerAnonymizer;
use Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaver;
use Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverInterface;
use Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverWithMultiShippingAddress;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Checker\PasswordResetExpirationChecker;
use Spryker\Zed\Customer\Business\Customer\Checker\PasswordResetExpirationCheckerInterface;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\Customer\CustomerReader;
use Spryker\Zed\Customer\Business\Customer\CustomerReaderInterface;
use Spryker\Zed\Customer\Business\Customer\EmailValidator;
use Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpander;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CharacterSetCustomerPasswordPolicy;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyValidator;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyValidatorInterface;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\DenyListCustomerPasswordPolicy;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\LengthCustomerPasswordPolicy;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\SequenceCustomerPasswordPolicy;
use Spryker\Zed\Customer\Business\Executor\CustomerPluginExecutor;
use Spryker\Zed\Customer\Business\Executor\CustomerPluginExecutorInterface;
use Spryker\Zed\Customer\Business\Model\CustomerOrderSaver as ObsoleteCustomerOrderSaver;
use Spryker\Zed\Customer\Business\Model\PreConditionChecker;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGenerator;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface;
use Spryker\Zed\Customer\Business\Sales\CustomerOrderHydrator;
use Spryker\Zed\Customer\Business\StrategyResolver\OrderSaverStrategyResolver;
use Spryker\Zed\Customer\Business\StrategyResolver\OrderSaverStrategyResolverInterface;
use Spryker\Zed\Customer\Business\Validator\CustomerAddressCheckoutSalutationValidator;
use Spryker\Zed\Customer\Business\Validator\CustomerAddressCheckoutSalutationValidatorInterface;
use Spryker\Zed\Customer\Business\Validator\CustomerCheckoutSalutationValidator;
use Spryker\Zed\Customer\Business\Validator\CustomerCheckoutSalutationValidatorInterface;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class CustomerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    public function createCustomer()
    {
        $config = $this->getConfig();

        $customer = new Customer(
            $this->getQueryContainer(),
            $this->createCustomerReferenceGenerator(),
            $config,
            $this->createEmailValidator(),
            $this->getMailFacade(),
            $this->getPropelQueryLocale(),
            $this->getLocaleFacade(),
            $this->createCustomerExpander(),
            $this->createCustomerPasswordPolicyValidator(),
            $this->createPasswordResetExpirationChecker(),
            $this->createCustomerPluginExecutor(),
        );

        return $customer;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\Checker\PasswordResetExpirationCheckerInterface
     */
    public function createPasswordResetExpirationChecker(): PasswordResetExpirationCheckerInterface
    {
        return new PasswordResetExpirationChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createAddress(),
            $this->createCustomerExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Executor\CustomerPluginExecutorInterface
     */
    public function createCustomerPluginExecutor(): CustomerPluginExecutorInterface
    {
        return new CustomerPluginExecutor(
            $this->getPostCustomerRegistrationPlugins(),
            $this->getCustomerPostDeletePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyValidatorInterface
     */
    public function createCustomerPasswordPolicyValidator(): CustomerPasswordPolicyValidatorInterface
    {
        return new CustomerPasswordPolicyValidator($this->getConfig(), $this->getCustomerPasswordPolicies());
    }

    /**
     * @return array<\Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface>
     */
    public function getCustomerPasswordPolicies(): array
    {
        return [
            $this->createDenyListCustomerPasswordPolicy(),
            $this->createLengthCustomerPasswordPolicy(),
            $this->createSequenceCustomerPasswordPolicy(),
            $this->createCharacterSetCustomerPasswordPolicy(),
        ];
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function createDenyListCustomerPasswordPolicy(): CustomerPasswordPolicyInterface
    {
        return new DenyListCustomerPasswordPolicy($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function createLengthCustomerPasswordPolicy(): CustomerPasswordPolicyInterface
    {
        return new LengthCustomerPasswordPolicy($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function createSequenceCustomerPasswordPolicy(): CustomerPasswordPolicyInterface
    {
        return new SequenceCustomerPasswordPolicy($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyInterface
     */
    public function createCharacterSetCustomerPasswordPolicy(): CustomerPasswordPolicyInterface
    {
        return new CharacterSetCustomerPasswordPolicy($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    public function createAddress()
    {
        return new Address(
            $this->getQueryContainer(),
            $this->getCountryFacade(),
            $this->getLocaleFacade(),
            $this->createCustomerExpander(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    public function getCountryFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface
     */
    public function createCustomerReferenceGenerator(): CustomerReferenceGeneratorInterface
    {
        return new CustomerReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getStoreFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface
     */
    public function getSequenceNumberFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Model\CustomerOrderSaverInterface
     */
    public function createCustomerOrderSaver()
    {
        return new ObsoleteCustomerOrderSaver($this->createCustomer(), $this->createAddress());
    }

    /**
     * @deprecated Use {@link createCheckoutCustomerOrderSaverWithMultiShippingAddress()} instead.
     *
     * @return \Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverInterface
     */
    public function createCheckoutCustomerOrderSaver()
    {
        return new CustomerOrderSaver($this->createCustomer(), $this->createAddress());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverInterface
     */
    public function createCheckoutCustomerOrderSaverWithMultiShippingAddress(): CustomerOrderSaverInterface
    {
        return new CustomerOrderSaverWithMultiShippingAddress(
            $this->createCustomer(),
            $this->createAddress(),
            $this->getCustomerService(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Model\PreConditionCheckerInterface
     */
    public function createPreConditionChecker()
    {
        return new PreConditionChecker(
            $this->createCustomer(),
            $this->getUtilValidateService(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Anonymizer\CustomerAnonymizer
     */
    public function createCustomerAnonymizer()
    {
        return new CustomerAnonymizer(
            $this->getQueryContainer(),
            $this->createCustomer(),
            $this->createAddress(),
            $this->getCustomerAnonymizerPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface>
     */
    public function getCustomerAnonymizerPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_ANONYMIZER);
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getPropelQueryLocale(): SpyLocaleQuery
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PROPEL_QUERY_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Sales\CustomerOrderHydratorInterface
     */
    public function createCustomerOrderHydrator()
    {
        return new CustomerOrderHydrator(
            $this->createCustomer(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\EmailValidatorInterface
     */
    public function createEmailValidator()
    {
        return new EmailValidator(
            $this->getUtilValidateService(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface
     */
    public function getUtilValidateService()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return array<\Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface>
     */
    public function getCustomerTransferExpanderPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\CustomerExtension\Dependency\Plugin\PostCustomerRegistrationPluginInterface>
     */
    public function getPostCustomerRegistrationPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_POST_CUSTOMER_REGISTRATION);
    }

    /**
     * @return list<\Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPostDeletePluginInterface>
     */
    public function getCustomerPostDeletePlugins(): array
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_POST_DELETE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface
     */
    public function createCustomerExpander()
    {
        return new CustomerExpander(
            $this->getCustomerTransferExpanderPlugins(),
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createCheckoutCustomerOrderSaverWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\Customer\Business\StrategyResolver\OrderSaverStrategyResolverInterface
     */
    public function createCustomerOrderSaverStrategyResolver(): OrderSaverStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[OrderSaverStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCheckoutCustomerOrderSaver();
        };

        $strategyContainer[OrderSaverStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCheckoutCustomerOrderSaverWithMultiShippingAddress();
        };

        return new OrderSaverStrategyResolver($strategyContainer);
    }

    /**
     * @return \Spryker\Service\Customer\CustomerServiceInterface
     */
    public function getCustomerService(): CustomerServiceInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface
     */
    public function getStoreFacade(): CustomerToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Validator\CustomerCheckoutSalutationValidatorInterface
     */
    public function createCustomerCheckoutSalutationValidator(): CustomerCheckoutSalutationValidatorInterface
    {
        return new CustomerCheckoutSalutationValidator(
            $this->getRepository(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Validator\CustomerAddressCheckoutSalutationValidatorInterface
     */
    public function createCustomerAddressCheckoutSalutationValidator(): CustomerAddressCheckoutSalutationValidatorInterface
    {
        return new CustomerAddressCheckoutSalutationValidator(
            $this->getRepository(),
            $this->getConfig(),
        );
    }
}
