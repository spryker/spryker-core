<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Persistence;

use Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery;

interface ProductValidityQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductValidity(): SpyProductValidityQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductValidityByIdProductConcrete(int $idProductConcrete): SpyProductValidityQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingValid(): SpyProductValidityQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingInvalid(): SpyProductValidityQuery;
}
