<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Communication\File;

use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UploadedFile extends SymfonyUploadedFile
{
    /**
     * The method checks if `UploadedFile::size` property exists to support BC for the `Symfony\HttpFoundation` of version lower than v4.1.0.
     *
     * @param string $path
     * @param string $originalName
     * @param string|null $mimeType
     * @param int|null $size
     * @param int|null $error
     * @param bool $test
     */
    public function __construct(
        string $path,
        string $originalName,
        ?string $mimeType = null,
        ?int $size = null,
        ?int $error = null,
        bool $test = false
    ) {
        if (property_exists($this, 'size')) {
            parent::__construct($path, $originalName, $mimeType, $size, $error, $test);

            return;
        }

        parent::__construct($path, $originalName, $mimeType, $error, $test);
    }
}
