<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExportExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generator;

interface DataEntityGeneratorPluginInterface extends DataEntityPluginInterface
{
    /**
     * Specification:
     * - Returns a generator that yields data entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generator<\Generated\Shared\Transfer\DataExportResultTransfer>
     */
    public function getBatchGenerator(DataExportConfigurationTransfer $dataExportConfigurationTransfer): Generator;
}
