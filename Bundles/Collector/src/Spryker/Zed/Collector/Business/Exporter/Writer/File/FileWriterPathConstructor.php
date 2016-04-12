<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Generated\Shared\Transfer\LocaleTransfer;

class FileWriterPathConstructor
{

    protected $exportDirPath;

    public function __construct($baseExportDir)
    {
        $this->exportDirPath = $baseExportDir;
    }

    public function getExportPath($type, LocaleTransfer $locale)
    {
        return $this->exportDirPath.'/'.$type.'_'.$locale->getLocaleName().'_beta.csv';
    }

}
