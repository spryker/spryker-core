<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Flysystem;

interface ResourceInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     *
     * @return void
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @param string $path
     *
     * @return void
     */
    public function setPath($path);

    /**
     * @return int
     */
    public function getTimestamp();

    /**
     * @param int $timestamp
     *
     * @return void
     */
    public function setTimestamp($timestamp);

    /**
     * @return string
     */
    public function getDirname();

    /**
     * @param string $dirname
     *
     * @return void
     */
    public function setDirname($dirname);

    /**
     * @return string
     */
    public function getBasename();

    /**
     * @param string $basename
     *
     * @return void
     */
    public function setBasename($basename);

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param string $filename
     *
     * @return void
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getExtension();

    /**
     * @param string $extension
     *
     * @return void
     */
    public function setExtension($extension);

    /**
     * @return int
     */
    public function getSize();

    /**
     * @param int $size
     *
     * @return void
     */
    public function setSize($size);

    /**
     * @return bool
     */
    public function isFolder();

}
