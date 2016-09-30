<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;

class ProductTouchManager implements ProductTouchManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     */
    public function __construct(ProductToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract)
    {
        $this->touchFacade->touchActive(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchActive(ProductConstants::RESOURCE_TYPE_PRODUCT_ATTRIBUTE_MAP, $idProductAbstract);
        $this->touchFacade->touchActive(ProductConstants::RESOURCE_TYPE_PRODUCT_URL, $idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract)
    {
        $this->touchFacade->touchInactive(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchInactive(ProductConstants::RESOURCE_TYPE_PRODUCT_ATTRIBUTE_MAP, $idProductAbstract);
        $this->touchFacade->touchInactive(ProductConstants::RESOURCE_TYPE_PRODUCT_URL, $idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract)
    {
        $this->touchFacade->touchDeleted(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchDeleted(ProductConstants::RESOURCE_TYPE_PRODUCT_ATTRIBUTE_MAP, $idProductAbstract);
        $this->touchFacade->touchDeleted(ProductConstants::RESOURCE_TYPE_PRODUCT_URL, $idProductAbstract);
    }

}
