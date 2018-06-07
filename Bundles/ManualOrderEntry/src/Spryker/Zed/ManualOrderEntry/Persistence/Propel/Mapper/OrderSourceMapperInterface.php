<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSource;

interface OrderSourceMapperInterface
{
    /**
     * @param \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSource $orderSourceEntity
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function mapOrderSourceEntityToTransfer(SpyOrderSource $orderSourceEntity): OrderSourceTransfer;
}
