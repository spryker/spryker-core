<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductOffer\Business\Deleter\PriceProductOfferDeleter;
use Spryker\Zed\PriceProductOffer\Business\Deleter\PriceProductOfferDeleterInterface;
use Spryker\Zed\PriceProductOffer\Business\Expander\ProductOfferExpander;
use Spryker\Zed\PriceProductOffer\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\PriceProductOffer\Business\Validator\Constraint\ValidCurrencyAssignedToStoreConstraint;
use Spryker\Zed\PriceProductOffer\Business\Validator\Constraint\ValidUniqueStoreCurrencyGrossNetConstraint;
use Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProvider;
use Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface;
use Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferValidator;
use Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferValidatorInterface;
use Spryker\Zed\PriceProductOffer\Business\Writer\PriceProductOfferWriter;
use Spryker\Zed\PriceProductOffer\Business\Writer\PriceProductOfferWriterInterface;
use Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOffer\PriceProductOfferDependencyProvider;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 */
class PriceProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Writer\PriceProductOfferWriterInterface
     */
    public function createPriceProductOfferWriter(): PriceProductOfferWriterInterface
    {
        return new PriceProductOfferWriter(
            $this->getPriceProductFacade(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Deleter\PriceProductOfferDeleterInterface
     */
    public function createPriceProductOfferDeleter(): PriceProductOfferDeleterInterface
    {
        return new PriceProductOfferDeleter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferValidatorInterface
     */
    public function createPriceProductOfferValidator(): PriceProductOfferValidatorInterface
    {
        return new PriceProductOfferValidator(
            $this->createPriceProductOfferConstraintProvider(),
            $this->getValidationAdapter()
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getPriceProductOfferValidatorConstraints(): array
    {
        return [
            $this->createValidCurrencyAssignedToStoreConstraint(),
            $this->createValidUniqueStoreCurrencyGrossNetPriceDataConstraint(),
        ];
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface
     */
    public function createPriceProductOfferConstraintProvider(): PriceProductOfferConstraintProviderInterface
    {
        return new PriceProductOfferConstraintProvider(
            $this->getPriceProductOfferValidatorConstraints()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductOfferToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidUniqueStoreCurrencyGrossNetPriceDataConstraint(): SymfonyConstraint
    {
        return new ValidUniqueStoreCurrencyGrossNetConstraint($this->getRepository());
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidCurrencyAssignedToStoreConstraint(): SymfonyConstraint
    {
        return new ValidCurrencyAssignedToStoreConstraint($this->getProvidedDependency(PriceProductOfferDependencyProvider::FACADE_STORE));
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface
     */
    public function getValidationAdapter(): PriceProductOfferToValidationAdapterInterface
    {
        return $this->getProvidedDependency(PriceProductOfferDependencyProvider::EXTERNAL_ADAPTER_VALIDATION);
    }
}
