<?php

namespace Spryker\Zed\ProductValidity\Dependency;


use Spryker\Zed\Product\Business\ProductFacadeInterface;

class ProductValidityToProductFacadeBridge implements ProductValidityToProductFacadeInterface
{
    /** @var  ProductFacadeInterface */
    protected $productFacade;

    /**
     * @param ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete(int $idProductConcrete): void
    {
        $this->productFacade->activateProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete(int $idProductConcrete): void
    {
        $this->productFacade->deactivateProductConcrete($idProductConcrete);
    }
}