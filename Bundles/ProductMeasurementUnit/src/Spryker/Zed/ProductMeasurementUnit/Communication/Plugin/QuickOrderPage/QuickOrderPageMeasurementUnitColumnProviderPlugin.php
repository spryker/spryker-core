<?php

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\QuickOrderPage;

use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormAdditionalDataColumnProviderPluginInterface;

class QuickOrderPageMeasurementUnitColumnProviderPlugin implements QuickOrderFormAdditionalDataColumnProviderPluginInterface
{
    protected const COLUMN_TITLE = 'quick-order.input-label.measurement_unit';
    protected const FIELD_NAME = 'measurementUnit';

    /**
     * @return string
     */
    public function getColumnTitle(): string
    {
        return static::COLUMN_TITLE;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return static::FIELD_NAME;
    }
}