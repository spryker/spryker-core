<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Builder;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;

interface ProductBuilderInterface
{

    /**
     * @param array $data
     *
     * @return ProductAbstractTransfer|ConcreteProductTransfer
     */
    public function buildProduct(array $data);

}
