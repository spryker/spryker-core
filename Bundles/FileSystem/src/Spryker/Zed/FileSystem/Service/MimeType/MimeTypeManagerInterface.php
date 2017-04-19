<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\MimeType;

interface MimeTypeManagerInterface
{

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getMimeTypeByFilename($filename);

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getExtensionByFilename($filename);

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function isImageByFilename($filename);

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function isPlainByFilename($filename);

}
