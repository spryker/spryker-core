<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;

interface ContentProductSetDataImportFacadeInterface
{
    public function importProductSet(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null);
}