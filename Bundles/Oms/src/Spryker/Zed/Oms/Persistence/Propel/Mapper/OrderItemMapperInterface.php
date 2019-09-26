<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

interface OrderItemMapperInterface
{
    /**
     * @param array $orderItemsMatrixResult
     *
     * @return array
     */
    public function mapOrderItemMatrix(array $orderItemsMatrixResult): array;
}
