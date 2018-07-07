<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint\UniqueProductPackagingUnitTypeNameConstraint;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider\ProductPackagingUnitTypeDataProvider;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider\ProductPackagingUnitTypeDataProviderInterface;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\ProductPackagingUnitTypeFormType;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Hydrator\OrderHydrator;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Hydrator\OrderHydratorInterface;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTable;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductFacadeInterface;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig getConfig()
 */
class ProductPackagingUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Communication\Hydrator\OrderHydratorInterface
     */
    public function createOrderHydrator(): OrderHydratorInterface
    {
        return new OrderHydrator(
            $this->getSalesOrderItemPropelQuery(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface
     */
    public function getProductPackagingUnitFacade(): ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::FACADE_PRODUCT_PACKAGING_UNIT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductPackagingUnitGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Communication\Table\ProductPackagingUnitTypeTable
     */
    public function createProductPackagingUnitTypeTable(): ProductPackagingUnitTypeTable
    {
        return new ProductPackagingUnitTypeTable(
            $this->getProductPackagingUnitTypePropelQuery(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductPackagingUnitGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::PROPEL_QUERY_SLAES_ORDER_ITEM);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider\ProductPackagingUnitTypeDataProviderInterface
     */
    public function createProductPackagingUnitTypeDataProvider(): ProductPackagingUnitTypeDataProviderInterface
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
            UniqueProductPackagingUnitTypeNameConstraint::OPTION_FACADE => $this->getProductPackagingUnitFacade(),
        ]);
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    public function getProductPackagingUnitTypePropelQuery(): SpyProductPackagingUnitTypeQuery
    {
        return $this->getProvidedDependency(ProductPackagingUnitGuiDependencyProvider::PROPEL_QUERY_PRODUCT_PACKAGING_UNIT_TYPE);
    }
}
