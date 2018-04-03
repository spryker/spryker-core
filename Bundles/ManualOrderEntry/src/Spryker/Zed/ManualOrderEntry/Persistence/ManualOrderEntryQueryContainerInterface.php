<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ManualOrderEntryQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idOrderSource
     *
     * @return \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery
     */
    public function queryOrderSourceById($idOrderSource);

    /**
     * @api
     *
     * @return \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery
     */
    public function queryOrderSource();
}
