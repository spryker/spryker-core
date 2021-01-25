<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OrderCustomReference\Business\Writer\OrderCustomReferenceWriter;
use Spryker\Zed\OrderCustomReference\Business\Writer\OrderCustomReferenceWriterInterface;

/**
 * @method \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 */
class OrderCustomReferenceBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OrderCustomReference\Business\Writer\OrderCustomReferenceWriterInterface
     */
    public function createOrderCustomReferenceWriter(): OrderCustomReferenceWriterInterface
    {
        return new OrderCustomReferenceWriter($this->getEntityManager(), $this->getConfig());
    }
}
