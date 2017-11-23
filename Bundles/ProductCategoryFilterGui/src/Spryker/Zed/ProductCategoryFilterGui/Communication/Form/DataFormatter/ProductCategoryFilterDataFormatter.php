<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Form\DataFormatter;

class ProductCategoryFilterDataFormatter
{
    /**
     * @param string $filterData
     *
     * @return array
     */
    public function formatFilterData($filterData)
    {
        $filterDataArray = json_decode($filterData, true);
        $formattedData = [];

        foreach ($filterDataArray as $data) {
            foreach ($data as $key => $value) {
                $formattedData[$key] = $value;
            }
        }
        return $formattedData;
    }
}
