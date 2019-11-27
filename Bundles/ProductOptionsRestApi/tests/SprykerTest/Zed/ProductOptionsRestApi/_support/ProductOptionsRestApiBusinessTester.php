<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CartItemRequestBuilder;
use Generated\Shared\DataBuilder\CartItemRequestProductOptionBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PersistentCartChangeBuilder;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ProductOptionsRestApi\Business\ProductOptionsRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOptionsRestApiBusinessTester extends Actor
{
    use _generated\ProductOptionsRestApiBusinessTesterActions;

    public const PRODUCT_CONCRETE_SKU = 'PRODUCT_CONCRETE_SKU';
    public const DIFFERENT_PRODUCT_CONCRETE_SKU = 'DIFFERENT_PRODUCT_CONCRETE_SKU';
    public const ID_PRODUCT_OPTION = '1';

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function buildCartItemRequestTransferWithOptions(): CartItemRequestTransfer
    {
        $productOptionBuilder = new CartItemRequestProductOptionBuilder(['idProductOption' => static::ID_PRODUCT_OPTION]);

        return (new CartItemRequestBuilder(['sku' => static::PRODUCT_CONCRETE_SKU]))
            ->withProductOption($productOptionBuilder)
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function buildCartItemRequestTransferWithoutOptions(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(['sku' => static::PRODUCT_CONCRETE_SKU]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function buildPersistentCartChangeTransfer(): PersistentCartChangeTransfer
    {
        $itemBuilder = new ItemBuilder(['sku' => static::PRODUCT_CONCRETE_SKU]);

        return (new PersistentCartChangeBuilder())
            ->withItem($itemBuilder)
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function buildPersistentCartChangeTransferWithDifferentSku(): PersistentCartChangeTransfer
    {
        $itemBuilder = new ItemBuilder(['sku' => static::DIFFERENT_PRODUCT_CONCRETE_SKU]);

        return (new PersistentCartChangeBuilder())
            ->withItem($itemBuilder)
            ->build();
    }
}
