<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business;

interface LogFacadeInterface
{
    /**
     * Specification:
     * - Deletes all specified log directories.
     *
     * @api
     *
     * @return void
     */
    public function deleteLogFileDirectories();
}
