<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProduct\Business\Checker\MerchantProductShoppingListItemChecker;
use Spryker\Zed\MerchantProduct\Business\Checker\MerchantProductShoppingListItemCheckerInterface;
use Spryker\Zed\MerchantProduct\Business\Expander\ShoppingListItemExpander;
use Spryker\Zed\MerchantProduct\Business\Expander\ShoppingListItemExpanderInterface;
use Spryker\Zed\MerchantProduct\Business\Reader\MerchantProductReader;
use Spryker\Zed\MerchantProduct\Business\Reader\MerchantProductReaderInterface;
use Spryker\Zed\MerchantProduct\Business\Validator\Constraint\ProductAbstractBelongsToMerchantConstraint;
use Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductCartValidator;
use Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductCartValidatorInterface;
use Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductValidator;
use Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductValidatorInterface;
use Spryker\Zed\MerchantProduct\Business\Writer\MerchantProductWriter;
use Spryker\Zed\MerchantProduct\Business\Writer\MerchantProductWriterInterface;
use Spryker\Zed\MerchantProduct\Dependency\External\MerchantProductToValidationAdapterInterface;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToMerchantFacadeInterface;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface;
use Spryker\Zed\MerchantProduct\MerchantProductDependencyProvider;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductEntityManagerInterface getEntityManager()
 */
class MerchantProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductCartValidatorInterface
     */
    public function createMerchantProductCartValidator(): MerchantProductCartValidatorInterface
    {
        return new MerchantProductCartValidator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Reader\MerchantProductReaderInterface
     */
    public function createMerchantProductReader(): MerchantProductReaderInterface
    {
        return new MerchantProductReader(
            $this->getRepository(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Writer\MerchantProductWriterInterface
     */
    public function createMerchantProductWriter(): MerchantProductWriterInterface
    {
        return new MerchantProductWriter(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Validator\MerchantProductValidatorInterface
     */
    public function createMerchantProductValidator(): MerchantProductValidatorInterface
    {
        return new MerchantProductValidator(
            $this->getValidationAdapter(),
            $this->getMerchantProductConstraints(),
        );
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    public function getMerchantProductConstraints(): array
    {
        return [
            $this->createProductAbstractBelongsToMerchantConstraint(),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createProductAbstractBelongsToMerchantConstraint(): Constraint
    {
        return new ProductAbstractBelongsToMerchantConstraint($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Expander\ShoppingListItemExpanderInterface
     */
    public function createShoppingListItemExpander(): ShoppingListItemExpanderInterface
    {
        return new ShoppingListItemExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface
     */
    public function getProductFacade(): MerchantProductToProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Dependency\External\MerchantProductToValidationAdapterInterface
     */
    public function getValidationAdapter(): MerchantProductToValidationAdapterInterface
    {
        return $this->getProvidedDependency(MerchantProductDependencyProvider::EXTERNAL_ADAPTER_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Checker\MerchantProductShoppingListItemCheckerInterface
     */
    public function createMerchantProductShoppingListItemChecker(): MerchantProductShoppingListItemCheckerInterface
    {
        return new MerchantProductShoppingListItemChecker(
            $this->getMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProductToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDependencyProvider::FACADE_MERCHANT);
    }
}
