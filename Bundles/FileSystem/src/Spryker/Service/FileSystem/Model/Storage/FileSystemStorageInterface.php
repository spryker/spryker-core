<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage;

interface FileSystemStorageInterface
{

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFileSystem();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $nameTokens
     *
     * @return string
     */
    public function generateValidName(array $nameTokens);

    /**
     * @param array $pathTokens
     *
     * @return string
     */
    public function generateValidPath(array $pathTokens);

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidFilenameException
     *
     * @return void
     */
    public function validateName($name);

}
