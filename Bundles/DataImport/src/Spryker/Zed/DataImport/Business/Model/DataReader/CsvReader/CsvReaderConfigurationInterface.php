<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader;

use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;

interface CsvReaderConfigurationInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @return $this
     */
    public function setDataImporterReaderConfigurationTransfer(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer);

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return string
     */
    public function getDelimiter();

    /**
     * @return string
     */
    public function getEnclosure();

    /**
     * @return string
     */
    public function getEscape();

    /**
     * @return int
     */
    public function getFlags();

    /**
     * @return bool
     */
    public function hasHeader();

    /**
     * @return int|null
     */
    public function getOffset();

    /**
     * @return int|null
     */
    public function getLimit();
}
