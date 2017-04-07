<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Mapper;

use Orm\Zed\Customer\Persistence\SpyCustomer;

class EntityMapper implements EntityMapperInterface
{

    /**
     * @param array $data
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    public function toEntity(array $data)
    {
        $productAbstractEntity = new SpyCustomer();
        $productAbstractEntity->fromArray($data);

        return $productAbstractEntity;
    }

    /**
     * @param array $data
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer[]
     */
    public function toEntityCollection(array $data)
    {
        $entityList = [];
        foreach ($data as $itemData) {
            $entityList[] = $this->toEntity($itemData);
        }

        return $entityList;
    }

}
