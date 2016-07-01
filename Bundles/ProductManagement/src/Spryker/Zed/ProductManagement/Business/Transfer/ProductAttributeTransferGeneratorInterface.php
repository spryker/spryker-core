<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Transfer;

use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInput;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadata;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeType;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalized;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue;
use Propel\Runtime\Collection\ObjectCollection;

interface ProductAttributeTransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute $productAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function convertProductAttribute(SpyProductManagementAttribute $productAttributeEntity);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function convertProductAttributeCollection(ObjectCollection $productAttributeEntityCollection);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalized $productAbstractLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer
     */
    public function convertProductAttributeLocalized(SpyProductManagementAttributeLocalized $productAbstractLocalizedEntity);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalized[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeLocalizedEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer[]
     */
    public function convertProductAttributeLocalizedCollection(ObjectCollection $productAttributeLocalizedEntityCollection);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadata $productAttributeMetadataEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer
     */
    public function convertProductAttributeMetadata(SpyProductManagementAttributeMetadata $productAttributeMetadataEntity);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadata[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeMetadataEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[]
     */
    public function convertProductAttributeMetadataCollection(ObjectCollection $productAttributeMetadataEntityCollection
    );

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInput $productAttributeInputEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeInputTransfer
     */
    public function convertProductAttributeInput(SpyProductManagementAttributeInput $productAttributeInputEntity);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInput[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeInputEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeInputTransfer[]
     */
    public function convertProductAttributeInputCollection(ObjectCollection $productAttributeInputEntityCollection);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeType $productAttributeTypeEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer
     */
    public function convertProductAttributeType(SpyProductManagementAttributeType $productAttributeTypeEntity);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeType[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeTypeEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer[]
     */
    public function convertProductAttributeTypeCollection(ObjectCollection $productAttributeTypeEntityCollection);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\Base\SpyProductManagementAttributeValue $productAttributeValueEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function convertProductAttributeValue(SpyProductManagementAttributeValue $productAttributeValueEntity);

    /**
     * @param \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValue[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeValueEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function convertProductAttributeValueCollection(ObjectCollection $productAttributeValueEntityCollection);

}
