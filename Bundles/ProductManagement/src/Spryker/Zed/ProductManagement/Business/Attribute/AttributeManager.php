<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGenerator;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGenerator
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(ProductManagementQueryContainerInterface $productManagementQueryContainer)
    {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGenerator
     */
    protected function getTransferGenerator()
    {
        if ($this->transferGenerator === null) {
            $this->transferGenerator = new ProductAttributeTransferGenerator();
        }

        return $this->transferGenerator;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[]
     */
    public function getProductAttributeMetadataCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeMetadata()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeMetadataCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeInputTransfer[]
     */
    public function getProductAttributesInputCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeMetadata()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeInputCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer[]
     */
    public function getProductAttributesTypeCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeMetadata()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeTypeCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function getProductAttributesValueCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeMetadata()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeValueCollection($collection);
    }

}
