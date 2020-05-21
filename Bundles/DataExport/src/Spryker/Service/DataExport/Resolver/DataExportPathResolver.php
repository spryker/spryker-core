<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Resolver;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;

class DataExportPathResolver implements DataExportPathResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param string $exportRootDir
     *
     * @return string
     */
    public function resolvePath(DataExportConfigurationTransfer $dataExportConfigurationTransfer, string $exportRootDir): string
    {
        $fullPath = $exportRootDir . DIRECTORY_SEPARATOR . $dataExportConfigurationTransfer->getDestination();
        $placeholders = [];
        foreach ($dataExportConfigurationTransfer->getHooks() as $hookKey => $hookValue) {
            $placeholders[sprintf('{%s}', $hookKey)] = $hookValue;
        }

        return str_replace(array_keys($placeholders), $placeholders, $fullPath);
    }
}
