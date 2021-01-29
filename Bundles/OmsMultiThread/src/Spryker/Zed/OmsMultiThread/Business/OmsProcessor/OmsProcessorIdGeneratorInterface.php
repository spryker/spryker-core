<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsMultiThread\Business\OmsProcessor;

interface OmsProcessorIdGeneratorInterface
{
    /**
     * @return int
     */
    public function generateOmsProcessorIdentifier(): int;
}
