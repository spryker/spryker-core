<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Plugin;

interface DataImportBeforeImportHookInterface
{
    /**
     * Specification:
     * - This will be executed before the import was running.
     *
     * @api
     *
     * @return void
     */
    public function beforeImport();
}
