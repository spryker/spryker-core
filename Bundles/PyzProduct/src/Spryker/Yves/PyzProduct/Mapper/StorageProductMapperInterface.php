<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzProduct\Mapper;

use Symfony\Component\HttpFoundation\Request;

interface StorageProductMapperInterface
{

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productData, Request $request, array $selectedAttributes = []);

}
