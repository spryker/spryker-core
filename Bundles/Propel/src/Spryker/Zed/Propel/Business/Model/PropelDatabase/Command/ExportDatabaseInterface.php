<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Command;

interface ExportDatabaseInterface
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath);
}
