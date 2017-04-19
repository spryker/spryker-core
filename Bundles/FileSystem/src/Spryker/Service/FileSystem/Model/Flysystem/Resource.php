<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Flysystem;

class Resource implements ResourceInterface
{

    const TYPE = 'type';
    const PATH = 'path';
    const TIMESTAMP = 'timestamp';
    const DIR_NAME = 'dirname';
    const BASENAME = 'basename';
    const FILENAME = 'filename';
    const EXTENSION = 'extension';
    const SIZE = 'size';

    const TYPE_FILE = 'file';
    const TYPE_FOLDER = 'dir';

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getValue(self::TYPE);
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->setValue(self::TIMESTAMP, $type);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getValue(self::PATH);
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->setValue(self::PATH, $path);
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->getValue(self::TIMESTAMP);
    }

    /**
     * @param int $timestamp
     *
     * @return void
     */
    public function setTimestamp($timestamp)
    {
        $this->setValue(self::TIMESTAMP, $timestamp);
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->getValue(self::DIR_NAME);
    }

    /**
     * @param string $dirname
     *
     * @return void
     */
    public function setDirname($dirname)
    {
        $this->setValue(self::DIR_NAME, $dirname);
    }

    /**
     * @return string
     */
    public function getBasename()
    {
        return $this->getValue(self::BASENAME);
    }

    /**
     * @param string $basename
     *
     * @return void
     */
    public function setBasename($basename)
    {
        $this->setValue(self::BASENAME, $basename);
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->getValue(self::FILENAME);
    }

    /**
     * @param string $filename
     *
     * @return void
     */
    public function setFilename($filename)
    {
        $this->setValue(self::FILENAME, $filename);
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->getValue(self::EXTENSION);
    }

    /**
     * @param string $extension
     *
     * @return void
     */
    public function setExtension($extension)
    {
        $this->setValue(self::EXTENSION, $extension);
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->getValue(self::SIZE);
    }

    /**
     * @param int $size
     *
     * @return void
     */
    public function setSize($size)
    {
        $this->setValue(self::SIZE, $size);
    }

    /**
     * @return bool
     */
    public function isFolder()
    {
        return $this->getValue(self::TYPE) === self::TYPE_FOLDER;
    }

    /**
     * @param string $name
     *
     * @return null|mixed
     */
    protected function getValue($name)
    {
        if (!array_key_exists($name, $this->data)) {
            return null;
        }

        return $this->data[$name];
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    protected function setValue($name, $value)
    {
        $this->data[$name] = $value;
    }

}
