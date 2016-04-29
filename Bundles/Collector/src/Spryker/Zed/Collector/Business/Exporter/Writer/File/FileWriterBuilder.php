<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Library\Writer\Csv\CsvWriter;

class FileWriterBuilder implements FileWriterBuilderInterface
{

    /**
     * @var string
     */
    protected $baseExportDirPath;

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * @var string
     */
    protected $escape;

    /**
     * FileWriterBuilder constructor.
     * @param string $baseExportDir
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($baseExportDir, $delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        $this->baseExportDirPath = $baseExportDir;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriter
     */
    public function build($type, LocaleTransfer $localeTransfer)
    {
        $writerAdapter = new CsvWriter($this->getFullExportPath($type, $localeTransfer));
        $writerAdapter->setCsvFormat($this->delimiter, $this->enclosure, $this->escape);
        return new FileWriter($writerAdapter);
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @return string
     */
    public function getFullExportPath($type, LocaleTransfer $localeTransfer)
    {
        return $this->baseExportDirPath.'/'.$type.'_'.$localeTransfer->getLocaleName().'.csv';
    }

}
