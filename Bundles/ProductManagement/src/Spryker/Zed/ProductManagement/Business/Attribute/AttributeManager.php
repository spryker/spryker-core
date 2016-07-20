<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGeneratorInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGeneratorInterface $transferGenerator
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductAttributeTransferGeneratorInterface $transferGenerator
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->transferGenerator = $transferGenerator;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->innerJoinSpyProductAttributeKey()
            ->find();

        return $this->transferGenerator->convertProductAttributeCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function getProductAttributeValueCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValue()
            ->find();

        return $this->transferGenerator->convertProductAttributeValueCollection($collection);
    }

}
