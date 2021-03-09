<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner\Business\GracefulRunner;

use Generator;

interface GracefulRunnerInterface
{
    /**
     * @param \Generator $generator
     * @param string|null $throwableClassName
     *
     * @return int
     */
    public function run(Generator $generator, ?string $throwableClassName = null): int;
}
