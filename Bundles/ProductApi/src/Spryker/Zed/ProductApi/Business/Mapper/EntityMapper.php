<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\ProductApiTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;

class EntityMapper implements EntityMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function toEntity(array $data)
    {
        $productAbstractEntity = new SpyProductAbstract();

        $data = $this->mapAttributes($data);
        $productAbstractEntity->fromArray($data);

        return $productAbstractEntity;
    }

    /**
     * @param array $productApiDataCollection
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function toEntityCollection(array $productApiDataCollection)
    {
        $entityList = [];
        foreach ($productApiDataCollection as $productData) {
            $entityList[] = $this->toEntity($productData);
        }

        return $entityList;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mapAttributes(array $data)
    {
        if (array_key_exists(ProductApiTransfer::ATTRIBUTES, $data)) {
            $attributes = (array)$data[ProductApiTransfer::ATTRIBUTES];
            $data[ProductApiTransfer::ATTRIBUTES] = json_encode($attributes); //TODO inject util encoding
        } else {
            $data[ProductApiTransfer::ATTRIBUTES] = null;
        }

        return $data;
    }
}
