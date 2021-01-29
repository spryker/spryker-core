<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsMultiThread\Business\OmsProcessor;

use Spryker\Zed\OmsMultiThread\OmsMultiThreadConfig;

class OmsProcessorIdGenerator implements OmsProcessorIdGeneratorInterface
{
    /**
     * @var \Spryker\Zed\OmsMultiThread\OmsMultiThreadConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\OmsMultiThread\OmsMultiThreadConfig $config
     */
    public function __construct(OmsMultiThreadConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return int
     */
    public function generateOmsProcessorIdentifier(): int
    {
        return rand(1, $this->config->getNumberOfOmsProcessWorkers());
    }
}
