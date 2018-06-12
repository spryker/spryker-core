<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Dependency\Facade;

use Generated\Shared\Transfer\ProductSuggestionDetailsTransfer;

class ProductAlternativeToProductFacadeBridge implements ProductAlternativeToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $suggestion
     *
     * @return \Generated\Shared\Transfer\ProductSuggestionDetailsTransfer
     */
    public function getSuggestionDetails(string $suggestion): ProductSuggestionDetailsTransfer
    {
        return $this->productFacade->getSuggestionDetails($suggestion);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract)
    {
        return $this->productFacade->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProductConcrete)
    {
        return $this->productFacade->findProductConcreteById($idProductConcrete);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract): bool
    {
        return $this->productFacade->isProductActive($idProductAbstract);
    }
}
