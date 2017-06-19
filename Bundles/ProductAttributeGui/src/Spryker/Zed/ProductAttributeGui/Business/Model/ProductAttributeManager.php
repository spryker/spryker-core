<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ProductAttributeManager
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @return void
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        $abstractAttributes = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
            ->endUse()
            ->find();

        print_r($abstractAttributes->toArray());
    }

}
