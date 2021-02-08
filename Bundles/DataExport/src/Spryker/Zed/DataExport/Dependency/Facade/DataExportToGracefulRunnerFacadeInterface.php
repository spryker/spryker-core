<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Dependency\Facade;

use Generator;

interface DataExportToGracefulRunnerFacadeInterface
{
    /**
     * @param \Generator $generator
     *
     * @return int
     */
    public function run(Generator $generator): int;
}
