<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataProvider;

use Spryker\Zed\ProductSetGui\Communication\Form\ReorderProductSetsFormType;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface;

class ReorderProductSetsFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface
     */
    protected $productSetGuiQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer
     */
    public function __construct(ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer)
    {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $productSetWeights = $this->productSetGuiQueryContainer->queryProductSetWeights()
            ->find()
            ->toKeyValue('PrimaryKey', 'weight');

        return [
            ReorderProductSetsFormType::FIELD_PRODUCT_SET_WEIGHTS => $productSetWeights,
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
