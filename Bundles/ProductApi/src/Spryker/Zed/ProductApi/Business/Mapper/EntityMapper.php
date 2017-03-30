<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\ProductApiTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;

class EntityMapper implements EntityMapperInterface
{

    /**
     * @var \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     */
    public function __construct(ProductApiToApiInterface $apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param array|\Generated\Shared\Transfer\ProductApiTransfer $data
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function toEntity(array $data)
    {
        $productAbstractEntity = new SpyProductAbstract();
        $data = $data->toArray();

        if (array_key_exists(ProductApiTransfer::ATTRIBUTES, $data)) {
            $attributes = (array)$data[ProductApiTransfer::ATTRIBUTES];
            if ($attributes) {
                $data[ProductApiTransfer::ATTRIBUTES] = json_encode($attributes); //TODO inject util encoding
            }
        }

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

}
