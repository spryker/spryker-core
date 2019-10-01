<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck\ProductPackagingUnitCartPreCheck;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck\ProductPackagingUnitCartPreCheckInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck\ProductPackagingUnitCheckoutPreCheck;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck\ProductPackagingUnitCheckoutPreCheckInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\ProductPackagingUnitAvailabilityHandler;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\ProductPackagingUnitAvailabilityHandlerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Cart\ProductPackagingUnitCartOperation;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Cart\ProductPackagingUnitCartOperationInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\AmountGroupKeyItemExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\AmountGroupKeyItemExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\AmountSalesUnitItemExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\AmountSalesUnitItemExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\ProductPackagingUnitItemExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\ProductPackagingUnitItemExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountLeadProductHydrateOrder;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountLeadProductHydrateOrderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountSalesUnitHydrateOrder;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountSalesUnitHydrateOrderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstaller;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstallerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\OrderItemExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\OrderItemExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\ProductPackagingUnitItemQuantityValidator;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\ProductPackagingUnitItemQuantityValidatorInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\SplittableOrderItemTransformer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\SplittableOrderItemTransformerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange\PriceChangeExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange\PriceChangeExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitAmountSalesUnitValue;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitAmountSalesUnitValueInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitGroupKeyGenerator;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitGroupKeyGeneratorInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeKeyGenerator;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeKeyGeneratorInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationWriter;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationWriterInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriter;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriterInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation\ProductPackagingUnitReservationHandler;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation\ProductPackagingUnitReservationHandlerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Validator\ProductPackagingUnitAmountRestrictionValidator;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Validator\ProductPackagingUnitAmountRestrictionValidatorInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilTextServiceInterface;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstallerInterface
     */
    public function createProductPackagingUnitTypeInstaller(): ProductPackagingUnitTypeInstallerInterface
    {
        return new ProductPackagingUnitTypeInstaller(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface
     */
    public function createProductPackagingUnitTypeReader(): ProductPackagingUnitTypeReaderInterface
    {
        return new ProductPackagingUnitTypeReader(
            $this->getRepository(),
            $this->createProductPackagingUnitTypeTranslationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriterInterface
     */
    public function createProductPackagingUnitTypeWriter(): ProductPackagingUnitTypeWriterInterface
    {
        return new ProductPackagingUnitTypeWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createProductPackagingUnitTypeTranslationWriter(),
            $this->createProductPackagingUnitTypeKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReaderInterface
     */
    public function createProductPackagingUnitTypeTranslationReader(): ProductPackagingUnitTypeTranslationReaderInterface
    {
        return new ProductPackagingUnitTypeTranslationReader(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationWriterInterface
     */
    public function createProductPackagingUnitTypeTranslationWriter(): ProductPackagingUnitTypeTranslationWriterInterface
    {
        return new ProductPackagingUnitTypeTranslationWriter(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductPackagingUnitToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\ProductPackagingUnitAvailabilityHandlerInterface
     */
    public function createProductPackagingUnitAvailabilityHandler(): ProductPackagingUnitAvailabilityHandlerInterface
    {
        return new ProductPackagingUnitAvailabilityHandler(
            $this->getRepository(),
            $this->getAvailabilityFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation\ProductPackagingUnitReservationHandlerInterface
     */
    public function createProductPackagingUnitReservationHandler(): ProductPackagingUnitReservationHandlerInterface
    {
        return new ProductPackagingUnitReservationHandler(
            $this->getRepository(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck\ProductPackagingUnitCartPreCheckInterface
     */
    public function createProductPackagingUnitCartPreCheck(): ProductPackagingUnitCartPreCheckInterface
    {
        return new ProductPackagingUnitCartPreCheck(
            $this->getRepository(),
            $this->getAvailabilityFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck\ProductPackagingUnitCheckoutPreCheckInterface
     */
    public function createProductPackagingUnitCheckoutPreCheck(): ProductPackagingUnitCheckoutPreCheckInterface
    {
        return new ProductPackagingUnitCheckoutPreCheck(
            $this->getAvailabilityFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Cart\ProductPackagingUnitCartOperationInterface
     */
    public function createProductPackagingUnitCartOperation(): ProductPackagingUnitCartOperationInterface
    {
        return new ProductPackagingUnitCartOperation();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ProductPackagingUnitToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface
     */
    public function getAvailabilityFacade(): ProductPackagingUnitToAvailabilityFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface
     */
    public function getOmsFacade(): ProductPackagingUnitToOmsFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductPackagingUnitToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface
     */
    public function getProductFacade(): ProductPackagingUnitToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface
     */
    public function getSalesQuantityFacade(): ProductPackagingUnitToSalesQuantityFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_SALES_QUANTITY);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\AmountGroupKeyItemExpanderInterface
     */
    public function createAmountGroupKeyItemExpander(): AmountGroupKeyItemExpanderInterface
    {
        return new AmountGroupKeyItemExpander(
            $this->createProductPackagingUnitGroupKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\AmountSalesUnitItemExpanderInterface
     */
    public function createAmountSalesUnitItemExpander(): AmountSalesUnitItemExpanderInterface
    {
        return new AmountSalesUnitItemExpander(
            $this->getProductMeasurementUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\ProductPackagingUnitItemExpanderInterface
     */
    public function createProductPackagingUnitItemExpander(): ProductPackagingUnitItemExpanderInterface
    {
        return new ProductPackagingUnitItemExpander(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    public function getProductMeasurementUnitFacade(): ProductPackagingUnitToProductMeasurementUnitFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_PRODUCT_MEASUREMENT_UNIT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange\PriceChangeExpanderInterface
     */
    public function createPriceChangeExpander(): PriceChangeExpanderInterface
    {
        return new PriceChangeExpander();
    }

   /**
    * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitAmountSalesUnitValueInterface
    */
    public function createProductPackagingUnitAmountSalesUnitValue(): ProductPackagingUnitAmountSalesUnitValueInterface
    {
        return new ProductPackagingUnitAmountSalesUnitValue();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitGroupKeyGeneratorInterface
     */
    public function createProductPackagingUnitGroupKeyGenerator(): ProductPackagingUnitGroupKeyGeneratorInterface
    {
        return new ProductPackagingUnitGroupKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Validator\ProductPackagingUnitAmountRestrictionValidatorInterface
     */
    public function createProductPackagingUnitAmountRestrictionValidator(): ProductPackagingUnitAmountRestrictionValidatorInterface
    {
        return new ProductPackagingUnitAmountRestrictionValidator(
            $this->getRepository(),
            $this->getProductMeasurementUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeKeyGeneratorInterface
     */
    public function createProductPackagingUnitTypeKeyGenerator(): ProductPackagingUnitTypeKeyGeneratorInterface
    {
        return new ProductPackagingUnitTypeKeyGenerator(
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilTextServiceInterface
     */
    public function getUtilTextService(): ProductPackagingUnitToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountLeadProductHydrateOrderInterface
     */
    public function createAmountLeadProductHydrateOrder(): AmountLeadProductHydrateOrderInterface
    {
        return new AmountLeadProductHydrateOrder(
            $this->getRepository(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountSalesUnitHydrateOrderInterface
     */
    public function createAmountSalesUnitHydrateOrder(): AmountSalesUnitHydrateOrderInterface
    {
        return new AmountSalesUnitHydrateOrder(
            $this->getRepository(),
            $this->getProductMeasurementUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\SplittableOrderItemTransformerInterface
     */
    public function createSplittableOrderItemTransformer(): SplittableOrderItemTransformerInterface
    {
        return new SplittableOrderItemTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\ProductPackagingUnitItemQuantityValidatorInterface
     */
    public function createProductPackagingUnitItemQuantityValidator(): ProductPackagingUnitItemQuantityValidatorInterface
    {
        return new ProductPackagingUnitItemQuantityValidator(
            $this->getSalesQuantityFacade()
        );
    }
}
