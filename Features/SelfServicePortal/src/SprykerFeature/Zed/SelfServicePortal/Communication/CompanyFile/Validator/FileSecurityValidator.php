<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Validator;

use RuntimeException;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSecurityValidator implements FileSecurityValidatorInterface
{
    /**
     * @var string
     */
    protected const VALIDATION_KEY_VALID = 'valid';

    /**
     * @var string
     */
    protected const VALIDATION_KEY_ERROR = 'error';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_UPLOAD_FAILED = 'File upload failed.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_FILE_TYPE = 'Invalid file type. Only CSV files are allowed.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_TOO_LARGE = 'File too large. Maximum size is 1MB.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_NOT_READABLE = 'File is not readable.';

    public function __construct(protected SelfServicePortalConfig $config)
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $uploadedFile
     *
     * @return array<string, string|bool>
     */
    public function validateUploadedFile(?UploadedFile $uploadedFile): array
    {
        if (!$uploadedFile || !$uploadedFile->isValid()) {
            return [static::VALIDATION_KEY_VALID => false, static::VALIDATION_KEY_ERROR => static::ERROR_MESSAGE_FILE_UPLOAD_FAILED];
        }

        if (!in_array($uploadedFile->getMimeType(), $this->config->getAllowedUploadMimeTypes(), true)) {
            return [static::VALIDATION_KEY_VALID => false, static::VALIDATION_KEY_ERROR => static::ERROR_MESSAGE_INVALID_FILE_TYPE];
        }

        if ($uploadedFile->getSize() > $this->config->getMaxUploadFileSize()) {
            return [static::VALIDATION_KEY_VALID => false, static::VALIDATION_KEY_ERROR => static::ERROR_MESSAGE_FILE_TOO_LARGE];
        }

        $filePath = $uploadedFile->getPathname();
        if (!is_readable($filePath)) {
            return [static::VALIDATION_KEY_VALID => false, static::VALIDATION_KEY_ERROR => static::ERROR_MESSAGE_FILE_NOT_READABLE];
        }

        return [static::VALIDATION_KEY_VALID => true];
    }

    /**
     * @param string $filePath
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function readFileSecurely(string $filePath): string
    {
        $realPath = realpath($filePath);
        if ($realPath === false) {
            throw new RuntimeException('Invalid file path: ' . $filePath);
        }
        $content = file_get_contents($realPath);
        if ($content === false) {
            throw new RuntimeException('Failed to read file: ' . $realPath);
        }

        return $content;
    }
}
