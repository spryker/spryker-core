<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface;

class ProductAlternativeGuiDataProvider
{
    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct(
        ProductAlternativeGuiToProductFacadeInterface $productFacade,
        ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @param int|null $idProductAlternative
     *
     * @return array
     */
    public function getData(?int $idProductAlternative = null): array
    {
        $productAlternativeTransfer = new ProductAlternativeTransfer();

        if ($idProductAlternative !== null) {
            $productAlternativeTransfer->setIdProductAlternative($idProductAlternative);

            $productAlternativeTransfer = $this
                ->productAlternativeFacade
                ->getProductAlternativeByIdProductAlternative($productAlternativeTransfer);
        }

        return $productAlternativeTransfer->modifiedToArray();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }
}
