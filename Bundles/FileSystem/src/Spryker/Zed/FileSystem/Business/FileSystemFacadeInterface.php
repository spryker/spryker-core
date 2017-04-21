<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 * @method \Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory getFactory()
 */
interface FileSystemFacadeInterface
{

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false The file contents or false on failure.
     */
    public function getMimeType($fileSystemName, $path);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     * @param string $newpath
     *
     * @return string|false The file contents or false on failure.
     */
    public function copy($fileSystemName, $path, $newpath);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     *
     * @return bool
     */
    public function delete($fileSystemName, $path);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($fileSystemName, $path);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     *
     * @return bool True on success, false on failure.
     */
    public function put($fileSystemName, $path, $content);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function read($fileSystemName, $path);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $newpath
     * @param string $path
     *
     * @return string|false
     */
    public function rename($fileSystemName, $path, $newpath);

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     *
     * @return bool True on success, false on failure.
     */
    public function write($fileSystemName, $path, $content);

}
