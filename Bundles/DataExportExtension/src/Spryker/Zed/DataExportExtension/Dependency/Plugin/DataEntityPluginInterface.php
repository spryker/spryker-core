<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExportExtension\Dependency\Plugin;

interface DataEntityPluginInterface
{
    /**
     * Specification:
     * - Returns the data entity name.
     *
     * @api
     *
     * @return string
     */
    public static function getDataEntity(): string;
}
