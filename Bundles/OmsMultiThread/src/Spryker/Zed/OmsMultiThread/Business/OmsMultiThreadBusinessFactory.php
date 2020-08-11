<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsMultiThread\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OmsMultiThread\Business\OmsProcessor\OmsProcessorIdGenerator;
use Spryker\Zed\OmsMultiThread\Business\OmsProcessor\OmsProcessorIdGeneratorInterface;
use Spryker\Zed\OmsMultiThread\Business\OrderExpander\OrderExpander;
use Spryker\Zed\OmsMultiThread\Business\OrderExpander\OrderExpanderInterface;

/**
 * @method \Spryker\Zed\OmsMultiThread\OmsMultiThreadConfig getConfig()
 */
class OmsMultiThreadBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OmsMultiThread\Business\OmsProcessor\OmsProcessorIdGeneratorInterface
     */
    public function createOmsProcessorIdGenerator(): OmsProcessorIdGeneratorInterface
    {
        return new OmsProcessorIdGenerator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\OmsMultiThread\Business\OrderExpander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->createOmsProcessorIdGenerator());
    }
}
