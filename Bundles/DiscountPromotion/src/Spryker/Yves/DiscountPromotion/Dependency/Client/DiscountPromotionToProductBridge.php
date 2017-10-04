<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion\Dependency\Client;

class DiscountPromotionToProductBridge implements DiscountPromotionToProductInterface
{

    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    protected $productClient;

    /**
     * @param \Spryker\Client\Product\ProductClientInterface $productClient
     */
    public function __construct($productClient)
    {
        $this->productClient = $productClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract)
    {
        return $this->productClient->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);
    }

}
