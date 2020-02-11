<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Business;

/**
 * @api
 *
 * @method \Spryker\Zed\Testify\Business\TestifyBusinessFactory getFactory()
 */
interface TestifyFacadeInterface
{
    /**
     * Specification:
     * - Removes all files in configured directories.
     * - Directories are configured in `\Spryker\Zed\Testify\TestifyConfig::getOutputDirectoriesForCleanup()`.
     *
     * @api
     *
     * @return array
     */
    public function cleanUpOutputDirectories(): array;
}
