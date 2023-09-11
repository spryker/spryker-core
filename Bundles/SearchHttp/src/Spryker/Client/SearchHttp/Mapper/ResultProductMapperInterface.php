<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Mapper;

interface ResultProductMapperInterface
{
    /**
     * @param array<int, mixed> $products
     *
     * @return array<int, mixed>
     */
    public function mapSearchHttpProductsToOriginalProducts(array $products): array;
}
