<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

use Spryker\Shared\Library\Writer\Csv\CsvWriterInterface;

interface MultiadapterWriterInterface extends WriterInterface
{

    /**
     * @param \Spryker\Shared\Library\Writer\Csv\CsvWriterInterface $writerAdapter
     *
     * @return bool
     */
    public function setWriterAdapter(CsvWriterInterface $writerAdapter);

}
