<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\Attribute;

use Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorQueryContainerInterface;

class AttributeProvider implements AttributeProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(ProductDiscountConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return string[]
     */
    public function getAllAttributeTypes()
    {
        $attributeMetaData = $this->queryContainer
            ->queryProductAttributeKeys()
            ->find();

        return $attributeMetaData->toArray();
    }
}
