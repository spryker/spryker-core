<?php

namespace Spryker\Zed\ProductValidity\Dependency;

interface ProductValidityToProductFacadeInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete(int $idProductConcrete): void;

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete(int $idProductConcrete): void;
}