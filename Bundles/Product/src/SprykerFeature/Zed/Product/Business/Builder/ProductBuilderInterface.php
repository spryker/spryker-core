<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Builder;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;

interface ProductBuilderInterface
{

    /**
     * @param array $data
     *
     * @return AbstractProductTransfer|ConcreteProductTransfer
     */
    public function buildProduct(array $data);

}
