<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper;

use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Propel\Runtime\Collection\ObjectCollection;

interface ProductAttributeTransferMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function convertProductAttribute(SpyProductManagementAttribute $productAttributeEntity);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute> $productAttributeEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function convertProductAttributeCollection(ObjectCollection $productAttributeEntityCollection);

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue $productAttributeValueEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    public function convertProductAttributeValue(SpyProductManagementAttributeValue $productAttributeValueEntity);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue> $productAttributeValueEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeValueTransfer>
     */
    public function convertProductAttributeValueCollection(ObjectCollection $productAttributeValueEntityCollection);
}
