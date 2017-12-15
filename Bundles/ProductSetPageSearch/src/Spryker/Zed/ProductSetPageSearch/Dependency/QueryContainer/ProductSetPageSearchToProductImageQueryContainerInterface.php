<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Dependency\QueryContainer;

interface ProductSetPageSearchToProductImageQueryContainerInterface
{

    /**
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryImagesByIdProductImageSet($idProductImageSet);

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImage();

}
