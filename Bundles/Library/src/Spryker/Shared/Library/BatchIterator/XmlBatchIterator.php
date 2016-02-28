<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\BatchIterator;

use Spryker\Shared\Library\Exception\ResourceNotFoundException;

class XmlBatchIterator implements CountableIteratorInterface
{

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $xmlReader;

    /**
     * @var
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
     * @param int $chunkSize
     */
    public function __construct($filename, $rootNodeName, $chunkSize = -1)
    {
        $this->xmlFilename = $filename;
        $this->rootNodeName = $rootNodeName;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @throws \Spryker\Shared\Library\Exception\ResourceNotFoundException
     *
     * @return \SimpleXMLElement
     */
    protected function getXmlReader()
    {
        if ($this->xmlReader === null) {
            $this->xmlReader = new \SimpleXMLElement(file_get_contents($this->xmlFilename));

            if (!is_file($this->xmlFilename) || !is_readable($this->xmlFilename)) {
                throw new ResourceNotFoundException(sprintf(
                    'Could not open Yaml file "%s"',
                    $this->xmlFilename
                ));
            }
        }

        return $this->xmlReader;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->batchData;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function next()
    {
        if ($this->batchData === null) {
            $this->batchData = $this->getXmlReader()->{$this->rootNodeName};
        }

        $this->offset++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->batchData !== null && $this->offset === 0;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $this->next();
        return count($this->batchData);
    }

}
