<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Communication;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductMeasurementUnitGui\Communication\Form\ProductMeasurementUnitForm;
use Spryker\Zed\ProductMeasurementUnitGui\Communication\Table\ProductMeasurementUnitTable;
use Spryker\Zed\ProductMeasurementUnitGui\Dependency\Facade\ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductMeasurementUnitGui\ProductMeasurementUnitGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitGui\ProductMeasurementUnitGuiConfig getConfig()
 */
class ProductMeasurementUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null $formData
     * @param array|null $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductMeasurementUnitForm(?ProductMeasurementUnitTransfer $formData = null, ?array $formOptions = null): FormInterface
    {
        return $this->getFormFactory()->create(
            ProductMeasurementUnitForm::class,
            $formData,
            $formOptions ?? ['is_edit' => (bool)$formData],
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitGui\Communication\Table\ProductMeasurementUnitTable
     */
    public function createProductMeasurementUnitTable(): ProductMeasurementUnitTable
    {
        return new ProductMeasurementUnitTable(
            $this->getProductMeasurementUnitPropelQuery(),
            $this->getProductMeasurementUnitFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitGui\Dependency\Facade\ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface
     */
    public function getProductMeasurementUnitFacade(): ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitGuiDependencyProvider::FACADE_PRODUCT_MEASUREMENT_UNIT);
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery
     */
    public function getProductMeasurementUnitPropelQuery(): SpyProductMeasurementUnitQuery
    {
        return $this->getProvidedDependency(ProductMeasurementUnitGuiDependencyProvider::PROPEL_QUERY_PRODUCT_MEASUREMENT_UNIT);
    }
}
