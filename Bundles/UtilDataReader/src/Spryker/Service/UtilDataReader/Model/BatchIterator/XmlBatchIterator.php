<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\BatchIterator;

use Exception;

class XmlBatchIterator implements CountableIteratorInterface
{
    /**
     * @var string
     */
    protected $xmlFilename;

    /**
     * @var string
     */
    protected $rootNodeName;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = -1;

    /**
     * @var array
     */
    protected $batchData;

    /**
     * @param string $filename
     * @param string $rootNodeName
     * @param int $chunkSize
     */
    public function __construct($filename, $rootNodeName, $chunkSize = -1)
    {
        $this->xmlFilename = $filename;
        $this->rootNodeName = $rootNodeName;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->batchData;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        if ($this->batchData === null) {
            try {
                $xml = simplexml_load_string(
                    file_get_contents($this->xmlFilename),
                    'SimpleXMLElement',
                    LIBXML_NOCDATA
                );

                $this->batchData = json_decode(
                    json_encode($xml),
                    true
                );

                $this->batchData = $this->batchData[$this->rootNodeName];
            } catch (Exception $exception) {
                $this->batchData = [];
            }
        }

        $this->offset++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->batchData !== null && $this->offset === 0;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        $this->next();

        return count($this->batchData);
    }
}
