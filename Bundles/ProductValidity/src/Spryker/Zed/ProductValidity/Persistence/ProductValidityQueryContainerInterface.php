<?php

namespace Spryker\Zed\ProductValidity\Persistence;


use Orm\Zed\Product\Persistence\SpyProductValidityQuery;

interface ProductValidityQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductValidity();

    /**
     * @param int $idProductConcrete
     *
     * @return SpyProductValidityQuery
     */
    public function queryProductValidityByIdProductConcrete(int $idProductConcrete): SpyProductValidityQuery;


    /**
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingValid();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingInvalid();
}