<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

interface DataImporterDataSetWriterAwareInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface $dataImportWriter
     *
     * @return void
     */
    public function setDataSetWriter(DataSetWriterInterface $dataImportWriter);
}
