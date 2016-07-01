<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

class AttributeManager implements AttributeManagerInterface
{


    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    public function getProductAttributesTypeCollection()
    {
        $typeCollection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeType()
            ->find();

        $types = [];

        

        return $types;
    }

}
