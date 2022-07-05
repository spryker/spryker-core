<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader;

interface ProductConcreteReaderInterface
{
    /**
     * @param int $idProductList
     *
     * @return array<int, string>
     */
    public function getProductConcreteSkusIndexedByIdProductConcrete(int $idProductList): array;
}
