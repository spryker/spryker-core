<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner\Business;

use Generator;

interface GracefulRunnerFacadeInterface
{
    /**
     * Specification:
     * - Executes the passed \Generator until SIGTERM or SIGINT was received.
     * - Returns the number of executed iterations.
     *
     * @api
     *
     * @param \Generator $generator
     * @param string|null $throwableClassName
     *
     * @return int
     */
    public function run(Generator $generator, ?string $throwableClassName = null): int;
}
