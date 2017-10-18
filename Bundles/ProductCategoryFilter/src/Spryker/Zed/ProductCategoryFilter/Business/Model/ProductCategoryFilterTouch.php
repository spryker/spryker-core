<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;
use Spryker\Zed\ProductCategoryFilter\Dependency\Facade\ProductCategoryFilterToTouchInterface;

class ProductCategoryFilterTouch implements ProductCategoryFilterTouchInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Dependency\Facade\ProductCategoryFilterToTouchInterface
     */
    protected $touchFacade;

    /**
     * ProductCategoryFilterTouch constructor.
     *
     * @param \Spryker\Zed\ProductCategoryFilter\Dependency\Facade\ProductCategoryFilterToTouchInterface $touchFacade
     */
    public function __construct(ProductCategoryFilterToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return bool
     */
    public function touchProductCategoryFilterActive(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->touchFacade->touchActive(ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER, $productCategoryFilterTransfer->getFkCategory());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return bool
     */
    public function touchProductCategoryFilterDeleted(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->touchFacade->touchDeleted(ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER, $productCategoryFilterTransfer->getFkCategory());
    }
}
