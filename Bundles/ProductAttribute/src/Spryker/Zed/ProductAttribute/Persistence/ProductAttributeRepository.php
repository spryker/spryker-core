<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributePersistenceFactory getFactory()
 */
class ProductAttributeRepository extends AbstractRepository implements ProductAttributeRepositoryInterface
{
    /**
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function findSuperAttributesFromAttributesList(array $attributes): array
    {
        $superAttributes = [];

        $mapper = $this->getFactory()
            ->createProductAttributeMapper();

        $query = $this->getFactory()
            ->createProductManagementAttributeQuery()
            ->leftJoinWithSpyProductManagementAttributeValue()
            ->innerJoinSpyProductAttributeKey()
            ->useSpyProductAttributeKeyQuery()
                ->filterByKey($attributes, Criteria::IN)
                ->filterByIsSuper(true)
            ->enduse();

        $productManagementAttributeKeyEntityCollection = $query->find();

        foreach ($productManagementAttributeKeyEntityCollection as $productManagementAttributeEntity) {
            $productManagementAttributeTransfer = $mapper->mapProductManagementAttributeEntityToTransfer($productManagementAttributeEntity, new ProductManagementAttributeTransfer());
            $superAttributes[$productManagementAttributeTransfer->getKey()] = $productManagementAttributeTransfer;
        }

        return $superAttributes;
    }
}
