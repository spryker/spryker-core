<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Builder;

interface ProductBuilderInterface
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|\Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function buildProduct(array $data);

}
