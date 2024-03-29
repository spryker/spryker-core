<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Processor;

interface ProcessorInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @return array
     */
    public function __invoke(array $data);
}
