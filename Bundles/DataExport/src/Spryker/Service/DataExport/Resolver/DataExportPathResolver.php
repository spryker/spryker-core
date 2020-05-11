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
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolvePath(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportConfigurationTransfer
    {
        $destinationPath = $dataExportConfigurationTransfer->getDestination();
        $placeholders = array_map(function (string $hookValue, string $hookKey): string {
            return sprintf('{%s}', $hookKey);
        }, $dataExportConfigurationTransfer->getHooks());

        $destinationPathResolved = str_replace($placeholders, $dataExportConfigurationTransfer->getHooks(), $destinationPath);

        return $dataExportConfigurationTransfer->setDestination($destinationPathResolved);
    }
}
