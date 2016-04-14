<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Writer\Csv;

interface CsvFormatterInterface
{

    /**
     * @return string
     */
    public function getDelimiter();

    /**
     * @param string $delimiter
     *
     * @return void
     */
    public function setDelimiter($delimiter);

    /**
     * @return string
     */
    public function getEnclosure();

    /**
     * @param string $enclosure
     *
     * @return void
     */
    public function setEnclosure($enclosure);

    /**
     * @return string
     */
    public function getEscape();

    /**
     * @param string $escape
     *
     * @return void
     */
    public function setEscape($escape);

}
