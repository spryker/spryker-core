<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Product\Mapper;

/**
 * Interface FrontendProductBuilderInterface
 */
interface StorageProductMapperInterface
{

    /**
     * @param array $productData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productData, array $selectedAttributes);

}
