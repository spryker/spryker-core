<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilDataReader\Model\Reader\Csv;

interface CsvMetaInterface
{
    /**
     * @return \SplFileObject
     */
    public function getCsvFile();

    /**
     * @return array
     */
    public function getColumns();

    /**
     * @return string
     */
    public function getColumnsAsString();

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return string
     */
    public function getDelimiter();

    /**
     * @return string
     */
    public function getEnclosure();

    /**
     * @return int
     */
    public function getColumnsOffset();
}
