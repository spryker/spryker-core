<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource\OrderSourceManager;
use Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource\OrderSourceManagerInterface;

/**
 * @method \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryRepositoryInterface getRepository()
 */
class ManualOrderEntryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource\OrderSourceManagerInterface
     */
    public function createOrderSourceManager(): OrderSourceManagerInterface
    {
        return new OrderSourceManager(
            $this->getRepository()
        );
    }
}
