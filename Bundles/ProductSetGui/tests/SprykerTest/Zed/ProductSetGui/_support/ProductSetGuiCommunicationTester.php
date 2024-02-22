<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ProductSetGui;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductSetGuiCommunicationTester extends Actor
{
    use _generated\ProductSetGuiCommunicationTesterActions;

    /**
     * @var string
     */
    public const PRODUCT_SET_GUI_CREATE_URL = '/product-set-gui/create';

    /**
     * @var string
     */
    public const PRODUCTS_TO_ASSIGN_TAB_NAME = 'Select Products to assign';

    /**
     * @var string
     */
    public const PRICE_COLUMN_NAME = 'Price';

    /**
     * @var string
     */
    public const SELECTED_PRODUCTS_TABLE_SELECTOR = '//table[@id="selectedProductsTable"]//tr//th';

    /**
     * @var string
     */
    public const PRODUCTS_TAB_SELECTOR = '.nav-tabs a[href="#tab-content-products"]';

    /**
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }

    /**
     * @return $this
     */
    public function switchToAssignProductsTab()
    {
        $this->click(static::PRODUCTS_TAB_SELECTOR);

        return $this;
    }
}
