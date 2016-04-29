<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Generated\Shared\Transfer\LocaleTransfer;

interface FileWriterBuilderInterface
{

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriter
     */
    public function build($type, LocaleTransfer $localeTransfer);

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @return string
     */
    public function getFullExportPath($type, LocaleTransfer $localeTransfer);

}
