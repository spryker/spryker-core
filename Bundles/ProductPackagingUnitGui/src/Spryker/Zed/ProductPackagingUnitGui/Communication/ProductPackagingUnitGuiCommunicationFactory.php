<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint\UniqueProductPackagingUnitTypeNameConstraint;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider\ProductPackagingUnitTypeDataProvider;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\ProductPackagingUnitTypeFormType;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTable;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleInterface;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitInterface;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig getConfig()
 */
class ProductPackagingUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitInterface
     */
    public function getProductPackagingUnitFacade(): ProductPackagingUnitGuiToProductPackagingUnitInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::FACADE_PRODUCT_PACKAGING_UNIT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTable
     */
    public function createProductPackagingUnitTypeTable(): ProductPackagingUnitTypeTable
    {
        return new ProductPackagingUnitTypeTable(
            $this->getRepository(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleInterface
     */
    public function getLocaleFacade(): ProductPackagingUnitGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider\ProductPackagingUnitTypeDataProvider
     */
    public function createProductPackagingUnitTypeDataProvider(): ProductPackagingUnitTypeDataProvider
    {
        return new ProductPackagingUnitTypeDataProvider(
            $this->getProductPackagingUnitFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductPackagingUnitTypeForm(?ProductPackagingUnitTypeTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductPackagingUnitTypeFormType::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createUniqueProductPackagingUnitTypeNameConstraint()
    {
        return new UniqueProductPackagingUnitTypeNameConstraint([
            UniqueProductPackagingUnitTypeNameConstraint::OPTION_REPOSITORY => $this->getRepository(),
        ]);
    }
}
