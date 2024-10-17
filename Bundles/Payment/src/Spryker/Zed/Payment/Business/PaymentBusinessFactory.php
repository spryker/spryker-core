<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business;

use Spryker\Service\Payment\PaymentServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Payment\Business\Calculation\PaymentCalculator;
use Spryker\Zed\Payment\Business\Calculation\PaymentCalculatorInterface;
use Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor;
use Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutorInterface;
use Spryker\Zed\Payment\Business\Creator\PaymentMethodCreator;
use Spryker\Zed\Payment\Business\Creator\PaymentMethodCreatorInterface;
use Spryker\Zed\Payment\Business\Creator\PaymentProviderCreator;
use Spryker\Zed\Payment\Business\Creator\PaymentProviderCreatorInterface;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilder;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilder;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Business\EventEmitter\PaymentMessageOmsEventEmitter;
use Spryker\Zed\Payment\Business\EventEmitter\PaymentMessageOmsEventEmitterInterface;
use Spryker\Zed\Payment\Business\Expander\PaymentExpander;
use Spryker\Zed\Payment\Business\Expander\PaymentExpanderInterface;
use Spryker\Zed\Payment\Business\ForeignPayment\ForeignPayment;
use Spryker\Zed\Payment\Business\ForeignPayment\ForeignPaymentInterface;
use Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGenerator;
use Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapper;
use Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface;
use Spryker\Zed\Payment\Business\Mapper\QuoteDataMapper;
use Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface;
use Spryker\Zed\Payment\Business\MessageConsumer\PaymentMessageConsumer;
use Spryker\Zed\Payment\Business\MessageConsumer\PaymentMessageConsumerInterface;
use Spryker\Zed\Payment\Business\MessageEmitter\MessageEmitter;
use Spryker\Zed\Payment\Business\MessageEmitter\MessageEmitterInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodFinder;
use Spryker\Zed\Payment\Business\Method\PaymentMethodFinderInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReader;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdater;
use Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodUpdater;
use Spryker\Zed\Payment\Business\Method\PaymentMethodUpdaterInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodValidator;
use Spryker\Zed\Payment\Business\Method\PaymentMethodValidatorInterface as DeprecatedPaymentMethodValidatorInterface;
use Spryker\Zed\Payment\Business\Order\SalesPaymentHydrator;
use Spryker\Zed\Payment\Business\Order\SalesPaymentHydratorInterface;
use Spryker\Zed\Payment\Business\Order\SalesPaymentReader;
use Spryker\Zed\Payment\Business\Order\SalesPaymentReaderInterface;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaver;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderExistsValidator;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderPaymentMethodExistsValidator;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderPaymentMethodUniqueValidator;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderUniqueValidator;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorComposite;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface;
use Spryker\Zed\Payment\Business\Provider\PaymentProviderReader;
use Spryker\Zed\Payment\Business\Provider\PaymentProviderReaderInterface;
use Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodExistsValidator;
use Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodProviderExistsValidator;
use Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodUniqueValidator;
use Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorComposite;
use Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface;
use Spryker\Zed\Payment\Business\Writer\PaymentWriter;
use Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToKernelAppFacadeInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToMessageBrokerInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToOmsFacadeInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface;
use Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface;
use Spryker\Zed\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface;
use Spryker\Zed\Payment\Dependency\Service\PaymentToUtilTextServiceInterface;
use Spryker\Zed\Payment\PaymentDependencyProvider;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 */
class PaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface
     */
    public function createPaymentMethodReader(): PaymentMethodReaderInterface
    {
        return new PaymentMethodReader(
            $this->getPaymentMethodFilterPlugins(),
            $this->getConfig(),
            $this->getStoreFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface
     */
    public function createQuoteDataMapper(): QuoteDataMapperInterface
    {
        return new QuoteDataMapper();
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface
     */
    public function getLocaleFacade(): PaymentToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToKernelAppFacadeInterface
     */
    public function getKernelAppFacade(): PaymentToKernelAppFacadeInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_KERNEL_APP);
    }

    /**
     * @return \Spryker\Service\Payment\PaymentServiceInterface
     */
    public function getPaymentService(): PaymentServiceInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::SERVICE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodValidatorInterface
     */
    public function createPaymentMethodValidator(): DeprecatedPaymentMethodValidatorInterface
    {
        return new PaymentMethodValidator(
            $this->createPaymentMethodReader(),
            $this->getPaymentService(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface
     */
    public function createPaymentMethodEventMapper(): PaymentMethodEventMapperInterface
    {
        return new PaymentMethodEventMapper();
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Calculation\PaymentCalculatorInterface
     */
    public function createPaymentCalculator(): PaymentCalculatorInterface
    {
        return new PaymentCalculator();
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodFinderInterface
     */
    public function createPaymentMethodFinder(): PaymentMethodFinderInterface
    {
        return new PaymentMethodFinder($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface
     */
    public function createPaymentMethodStoreRelationUpdater(): PaymentMethodStoreRelationUpdaterInterface
    {
        return new PaymentMethodStoreRelationUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodUpdaterInterface
     */
    public function createPaymentMethodUpdater(): PaymentMethodUpdaterInterface
    {
        return new PaymentMethodUpdater(
            $this->getEntityManager(),
            $this->createPaymentMethodStoreRelationUpdater(),
            $this->getRepository(),
            $this->createPaymentWriter(),
            $this->createPaymentMethodKeyGenerator(),
            $this->createPaymentMethodEventMapper(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface
     */
    public function createPaymentMethodKeyGenerator(): PaymentMethodKeyGeneratorInterface
    {
        return new PaymentMethodKeyGenerator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Service\PaymentToUtilTextServiceInterface
     */
    public function getUtilTextService(): PaymentToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PaymentToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return array<\Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface>
     */
    public function getPaymentMethodFilterPlugins(): array
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::PAYMENT_METHOD_FILTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface
     */
    public function getStoreFacade(): PaymentToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface
     */
    public function createPaymentWriter(): PaymentWriterInterface
    {
        return new PaymentWriter($this->getEntityManager());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutorInterface
     */
    public function createCheckoutPaymentPluginExecutor(): PaymentPluginExecutorInterface
    {
        return new PaymentPluginExecutor($this->getCheckoutPlugins(), $this->createPaymentSaver());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface
     */
    public function createPaymentSaver(): SalesPaymentSaverInterface
    {
        return new SalesPaymentSaver($this->getQueryContainer());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface
     */
    public function getCheckoutPlugins(): CheckoutPluginCollectionInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::CHECKOUT_PLUGINS);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentHydratorInterface
     */
    public function createPaymentHydrator(): SalesPaymentHydratorInterface
    {
        return new SalesPaymentHydrator(
            $this->getPaymentHydrationPlugins(),
            $this->getQueryContainer(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface
     */
    public function getPaymentHydrationPlugins(): PaymentHydratorPluginCollectionInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::PAYMENT_HYDRATION_PLUGINS);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentReaderInterface
     */
    public function createSalesPaymentReader(): SalesPaymentReaderInterface
    {
        return new SalesPaymentReader(
            $this->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Provider\PaymentProviderReaderInterface
     */
    public function createPaymentProviderReader(): PaymentProviderReaderInterface
    {
        return new PaymentProviderReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToOmsFacadeInterface
     */
    public function getOmsFacade(): PaymentToOmsFacadeInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToMessageBrokerInterface
     */
    public function getMessageBrokerFacade(): PaymentToMessageBrokerInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Business\MessageEmitter\MessageEmitterInterface
     */
    public function createMessageEmitter(): MessageEmitterInterface
    {
        return new MessageEmitter($this->getMessageBrokerFacade());
    }

    /**
     * @return \Spryker\Zed\Payment\Business\EventEmitter\PaymentMessageOmsEventEmitterInterface
     */
    public function createPaymentMessageOmsEventEmitter(): PaymentMessageOmsEventEmitterInterface
    {
        return new PaymentMessageOmsEventEmitter(
            $this->getOmsFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Creator\PaymentProviderCreatorInterface
     */
    public function createPaymentProviderCreator(): PaymentProviderCreatorInterface
    {
        return new PaymentProviderCreator(
            $this->getEntityManager(),
            $this->createPaymentProviderCreateValidator(),
            $this->createPaymentProviderEntityIdentifierBuilder(),
            $this->createPaymentMethodEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface
     */
    public function createPaymentProviderCreateValidator(): PaymentProviderValidatorInterface
    {
        return new PaymentProviderValidatorComposite([
            $this->createPaymentProviderUniqueValidator(),
            $this->createPaymentProviderPaymentMethodUniqueValidator(),
            $this->createPaymentProviderExistsValidator(),
            $this->createPaymentProviderPaymentMethodExistsValidator(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface
     */
    public function createPaymentProviderExistsValidator(): PaymentProviderValidatorInterface
    {
        return new PaymentProviderExistsValidator(
            $this->getRepository(),
            $this->createPaymentProviderEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface
     */
    public function createPaymentProviderUniqueValidator(): PaymentProviderValidatorInterface
    {
        return new PaymentProviderUniqueValidator(
            $this->createPaymentProviderEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface
     */
    public function createPaymentProviderPaymentMethodExistsValidator(): PaymentProviderValidatorInterface
    {
        return new PaymentProviderPaymentMethodExistsValidator(
            $this->getRepository(),
            $this->createPaymentProviderEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface
     */
    public function createPaymentProviderPaymentMethodUniqueValidator(): PaymentProviderValidatorInterface
    {
        return new PaymentProviderPaymentMethodUniqueValidator(
            $this->createPaymentMethodEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface
     */
    public function createPaymentProviderEntityIdentifierBuilder(): PaymentProviderEntityIdentifierBuilderInterface
    {
        return new PaymentProviderEntityIdentifierBuilder();
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Creator\PaymentMethodCreatorInterface
     */
    public function createPaymentMethodCreator(): PaymentMethodCreatorInterface
    {
        return new PaymentMethodCreator(
            $this->getEntityManager(),
            $this->createPaymentMethodCreateValidator(),
            $this->createPaymentMethodEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface
     */
    public function createPaymentMethodCreateValidator(): PaymentMethodValidatorInterface
    {
        return new PaymentMethodValidatorComposite([
            $this->createPaymentMethodExistsValidator(),
            $this->createPaymentMethodUniqueValidator(),
            $this->createPaymentMethodProviderExistsValidator(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface
     */
    public function createPaymentMethodExistsValidator(): PaymentMethodValidatorInterface
    {
        return new PaymentMethodExistsValidator(
            $this->getRepository(),
            $this->createPaymentMethodEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface
     */
    public function createPaymentMethodProviderExistsValidator(): PaymentMethodValidatorInterface
    {
        return new PaymentMethodProviderExistsValidator(
            $this->getRepository(),
            $this->createPaymentMethodEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface
     */
    public function createPaymentMethodUniqueValidator(): PaymentMethodValidatorInterface
    {
        return new PaymentMethodUniqueValidator(
            $this->createPaymentMethodEntityIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface
     */
    public function createPaymentMethodEntityIdentifierBuilder(): PaymentMethodEntityIdentifierBuilderInterface
    {
        return new PaymentMethodEntityIdentifierBuilder();
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Expander\PaymentExpanderInterface
     */
    public function createPaymentExpander(): PaymentExpanderInterface
    {
        return new PaymentExpander(
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\MessageConsumer\PaymentMessageConsumerInterface
     */
    public function createPaymentMessageConsumer(): PaymentMessageConsumerInterface
    {
        return new PaymentMessageConsumer($this->createPaymentMethodUpdater());
    }

    /**
     * @return \Spryker\Zed\Payment\Business\ForeignPayment\ForeignPaymentInterface
     */
    public function createForeignPayment(): ForeignPaymentInterface
    {
        return new ForeignPayment(
            $this->createQuoteDataMapper(),
            $this->getLocaleFacade(),
            $this->getKernelAppFacade(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getPaymentService(),
            $this->getUtilEncodingService(),
        );
    }
}
