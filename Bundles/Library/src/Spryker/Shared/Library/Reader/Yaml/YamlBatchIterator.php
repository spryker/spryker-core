<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Reader\Yaml;

use Spryker\Shared\Library\Reader\CountableIteratorInterface;
use Spryker\Shared\Library\Reader\Exception\ResourceNotFoundException;
use Symfony\Component\Yaml\Yaml;

class YamlBatchIterator implements CountableIteratorInterface
{

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $yamlReader;

    /**
     * @var
     */
    protected $yamlFilename;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 10;

    /**
     * @var array
     */
    protected $batchData;

    /**
     * @param string $filename
     * @param int $chunkSize
     */
    public function __construct($filename, $chunkSize = -1)
    {
        $this->yamlFilename = $filename;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @throws \Spryker\Shared\Library\Reader\Exception\ResourceNotFoundException
     *
     * @return \Symfony\Component\Yaml\Yaml
     */
    protected function getYamlReader()
    {
        if ($this->yamlReader === null) {
            $this->yamlReader = new Yaml();

            if (!is_file($this->yamlFilename) || !is_readable($this->yamlFilename)) {
                throw new ResourceNotFoundException(sprintf(
                    'Could not open Yaml file "%s"',
                    $this->yamlFilename
                ));
            }
        }

        return $this->yamlReader;
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
            $this->batchData = $this->getYamlReader()->parse(
                file_get_contents($this->yamlFilename)
            );
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
