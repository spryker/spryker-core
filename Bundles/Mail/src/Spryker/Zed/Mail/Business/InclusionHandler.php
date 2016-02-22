<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business;

class InclusionHandler implements InclusionHandlerInterface
{

    /**
     * @param string $path
     *
     * @return string
     */
    public function guessType($path)
    {
        $mimeFileInfo = new \finfo(FILEINFO_MIME);

        return explode(';', $mimeFileInfo->file($path))[0];
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getFilename($path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        return $parts[count($parts) - 1];
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function encodeBase64($path)
    {
        return base64_encode(file_get_contents($path));
    }

}
