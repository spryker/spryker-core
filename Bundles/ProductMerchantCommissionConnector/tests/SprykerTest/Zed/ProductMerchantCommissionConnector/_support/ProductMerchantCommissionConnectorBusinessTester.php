<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantCommissionConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;

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
 * @method \Spryker\Zed\ProductMerchantCommissionConnector\Business\ProductMerchantCommissionConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductMerchantCommissionConnectorBusinessTester extends Actor
{
    use _generated\ProductMerchantCommissionConnectorBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductAttributeKeyTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductAttributeKeyQuery());
    }

    /**
     * @param int $idProductAbstract
     * @param array<string, string> $attributes
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductConcreteWithLocalizedAttributes(int $idProductAbstract, array $attributes = []): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->haveProductConcrete([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $idProductAbstract,
            ProductConcreteTransfer::ATTRIBUTES => $attributes,
        ]);

        $this->addLocalizedAttributesToProductConcrete(
            $productConcreteTransfer,
            $this->generateLocalizedAttributes(),
        );

        return $productConcreteTransfer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function getProductAttributeKeyQuery(): SpyProductAttributeKeyQuery
    {
        return SpyProductAttributeKeyQuery::create();
    }
}
