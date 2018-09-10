<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PriceProduct\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormAdditionalDataColumnProviderPluginInterface;

class QuickOrderPagePriceColumnProviderPlugin extends AbstractPlugin implements QuickOrderFormAdditionalDataColumnProviderPluginInterface
{
    protected const COLUMN_TITLE = 'quick-order.input-label.price';
    protected const FIELD_NAME = 'price';

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
