<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrixGui\Communication\Formatter;

interface OrderMatrixFormatterInterface
{
    /**
     * @param array<string, array<string, array<string>>> $orderMatrix
     *
     * @return array<int, array<string>>
     */
    public function formatOrderMatrix(array $orderMatrix): array;
}
