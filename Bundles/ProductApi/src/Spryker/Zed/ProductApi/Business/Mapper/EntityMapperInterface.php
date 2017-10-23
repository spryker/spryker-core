<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

interface EntityMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function toEntity(array $data);

    /**
     * @param array $productApiDataCollection
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function toEntityCollection(array $productApiDataCollection);
}
