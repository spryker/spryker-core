<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportExtension\Dependency\Plugin;

interface DataSetBulkWriterPluginInterface
{
    /**
     * Specification:
     * - Returns list of database engines that are compatible with current bulk writer plugin.
     *
     * @api
     *
     * @return string[]
     */
    public function getCompatibleDatabaseEngines();
}
