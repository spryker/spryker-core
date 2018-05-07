<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Spryker\Zed\DataImport\Business\Model\Writer\DataImportWriterInterface;

interface DataImporterWriterAwareInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\Writer\DataImportWriterInterface $dataImportWriter
     *
     * @return void
     */
    public function setDataImportWriter(DataImportWriterInterface $dataImportWriter);
}
