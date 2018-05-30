<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;

class ProductAlternativeCreator implements ProductAlternativeCreatorInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct(
        ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
    ) {
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @param string $searchName
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAlternative(string $searchName): ProductAlternativeResponseTransfer
    {
        return new ProductAlternativeResponseTransfer();
    }
}
