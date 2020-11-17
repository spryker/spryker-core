<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportExtension\Dependency\Plugin;

interface DataSetWriterPluginApplicableAwareInterface
{
    /**
     * Specification:
     * - Returns True if implemented plugin is applicable for current config, False otherwise.
     *
     * @return bool
     */
    public function isApplicable(): bool;
}
