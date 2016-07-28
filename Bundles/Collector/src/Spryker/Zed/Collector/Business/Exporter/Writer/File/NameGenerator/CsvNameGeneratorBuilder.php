<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator;

use Generated\Shared\Transfer\LocaleTransfer;

class CsvNameGeneratorBuilder
{

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator\CsvNameGenerator
     */
    public function createCsvNameGenerator($type, LocaleTransfer $localeTransfer)
    {
        return new CsvNameGenerator($type, $localeTransfer);
    }

}
