<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface RefundQueryContainerInterface extends QueryContainerInterface
{

    /**
     * Specification:
     * - Returns SpyRefundQuery ordered by id descending.
     *
     * @api
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function queryRefunds();

}
