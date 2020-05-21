<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Parser;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;

interface DataExportConfigurationParserInterface
{
    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function parseConfigurationFile(string $fileName): DataExportConfigurationsTransfer;
}
