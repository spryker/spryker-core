<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrixGui\Communication\DataExtractor;

interface OrderMatrixDataExtractorInterface
{
    /**
     * @param array<string, array<string, array<string>>> $orderMatrix
     *
     * @return array<int, string>
     */
    public function extractProcessNames(array $orderMatrix): array;

    /**
     * @param array<string, array<string, array<string>>> $orderMatrix
     *
     * @return array<int, string>
     */
    public function extractStateNames(array $orderMatrix): array;
}
