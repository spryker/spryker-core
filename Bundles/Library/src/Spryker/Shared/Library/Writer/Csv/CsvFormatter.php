<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Writer\Csv;

class CsvFormatter implements CsvFormatterInterface
{

    /**
     * @var string
     */
    protected $delimiter = ",";

    /**
     * @var string
     */
    protected $enclosure = "\"";

    /**
     * @var string
     */
    protected $escape = "\\";

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     *
     * @return void
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     *
     * @return void
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @return string
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * @param string $escape
     *
     * @return void
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
    }

}
