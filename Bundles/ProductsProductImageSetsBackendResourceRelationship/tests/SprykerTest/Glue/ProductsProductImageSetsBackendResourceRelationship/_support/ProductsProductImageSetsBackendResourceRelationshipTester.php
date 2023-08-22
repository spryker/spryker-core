<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductsProductImageSetsBackendResourceRelationship;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

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
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\ProductsProductImageSetsBackendResourceRelationship\PHPMD)
 */
class ProductsProductImageSetsBackendResourceRelationshipTester extends Actor
{
    use _generated\ProductsProductImageSetsBackendResourceRelationshipTesterActions;

    /**
     * @uses \Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    public function createProductConcreteResource(
        ProductConcreteTransfer $productConcreteTransfer
    ): GlueResourceTransfer {
        return (new GlueResourceTransfer())
            ->setId($productConcreteTransfer->getSku())
            ->setType(static::RESOURCE_CONCRETE_PRODUCTS)
            ->setAttributes(
                (new ProductConcretesBackendApiAttributesTransfer())->fromArray($productConcreteTransfer->toArray(), true),
            );
    }
}
