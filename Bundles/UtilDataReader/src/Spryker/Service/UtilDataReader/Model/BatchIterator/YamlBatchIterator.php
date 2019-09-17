<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\BatchIterator;

use Spryker\Service\UtilDataReader\Dependency\YamlReaderInterface;
use Spryker\Service\UtilDataReader\Exception\ResourceNotFoundException;

class YamlBatchIterator implements CountableIteratorInterface
{
    /**
     * @var \Spryker\Service\UtilDataReader\Dependency\YamlReaderInterface
     */
    protected $yamlReader;

    /**
     * @var string
     */
    protected $yamlFilename;

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
     * @param \Spryker\Service\UtilDataReader\Dependency\YamlReaderInterface $yamlReader
     * @param string $filename
     * @param int $chunkSize
     */
    public function __construct(YamlReaderInterface $yamlReader, $filename, $chunkSize = -1)
    {
        $this->yamlReader = $yamlReader;
        $this->yamlFilename = $filename;
        $this->chunkSize = $chunkSize;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Spryker\Service\UtilDataReader\Exception\ResourceNotFoundException
     *
     * @return void
     */
    protected function initialize()
    {
        if ($this->batchData === null) {
            if (!is_file($this->yamlFilename) || !is_readable($this->yamlFilename)) {
                throw new ResourceNotFoundException(sprintf(
                    'Could not open Yaml file "%s"',
                    $this->yamlFilename
                ));
            }
            $this->batchData = $this->yamlReader->parse(
                file_get_contents($this->yamlFilename)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $this->initialize();

        return $this->batchData;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function next()
    {
        $this->initialize();

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
        return ($this->batchData !== null && $this->offset === 0);
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
        $this->initialize();

        return count($this->batchData);
    }
}
