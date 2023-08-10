<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File as SymfonyFile;

/**
 * @uses \Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints\FileValidator
 */
class File extends SymfonyFile
{
    /**
     * @var string
     */
    public const INVALID_EXTENSION_ERROR = 'c8c7315c-6186-4719-8b71-5659e16bdcb7';

    /**
     * @var array<string, mixed>|string|null
     */
    public array|string|null $extensions = [];

    /**
     * @var string
     */
    public $uploadExtensionErrorMessage = 'A PHP extension caused the upload to fail.';

    /**
     * @var string
     */
    public string $extensionsMessage = 'The extension of the file is invalid ({{ extension }}). Allowed extensions are {{ extensions }}.';

    /**
     * @param array<mixed>|null $options
     * @param string|int|null $maxSize
     * @param bool|null $binaryFormat
     * @param list<string>|string|null $mimeTypes
     * @param string|null $notFoundMessage
     * @param string|null $notReadableMessage
     * @param string|null $maxSizeMessage
     * @param string|null $mimeTypesMessage
     * @param string|null $disallowEmptyMessage
     * @param string|null $uploadIniSizeErrorMessage
     * @param string|null $uploadFormSizeErrorMessage
     * @param string|null $uploadPartialErrorMessage
     * @param string|null $uploadNoFileErrorMessage
     * @param string|null $uploadNoTmpDirErrorMessage
     * @param string|null $uploadCantWriteErrorMessage
     * @param string|null $uploadExtensionErrorMessage
     * @param string|null $uploadErrorMessage
     * @param array<mixed>|null $groups
     * @param mixed|null $payload
     * @param array<mixed>|string|null $extensions
     * @param string|null $extensionsMessage
     */
    public function __construct(
        ?array $options = null,
        int|string|null $maxSize = null,
        ?bool $binaryFormat = null,
        array|string|null $mimeTypes = null,
        ?string $notFoundMessage = null,
        ?string $notReadableMessage = null,
        ?string $maxSizeMessage = null,
        ?string $mimeTypesMessage = null,
        ?string $disallowEmptyMessage = null,
        ?string $uploadIniSizeErrorMessage = null,
        ?string $uploadFormSizeErrorMessage = null,
        ?string $uploadPartialErrorMessage = null,
        ?string $uploadNoFileErrorMessage = null,
        ?string $uploadNoTmpDirErrorMessage = null,
        ?string $uploadCantWriteErrorMessage = null,
        ?string $uploadExtensionErrorMessage = null,
        ?string $uploadErrorMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        array|string|null $extensions = null,
        ?string $extensionsMessage = null
    ) {
        parent::__construct(
            $options,
            $maxSize,
            $binaryFormat,
            $mimeTypes,
            $notFoundMessage,
            $notReadableMessage,
            $maxSizeMessage,
            $mimeTypesMessage,
            $disallowEmptyMessage,
            $uploadIniSizeErrorMessage,
            $uploadFormSizeErrorMessage,
            $uploadPartialErrorMessage,
            $uploadNoFileErrorMessage,
            $uploadNoTmpDirErrorMessage,
            $uploadCantWriteErrorMessage,
            $uploadExtensionErrorMessage,
            $uploadErrorMessage,
            $groups,
            $payload,
        );

        $this->extensions = $extensions ?? $this->extensions;
        $this->extensionsMessage = $extensionsMessage ?? $this->extensionsMessage;
    }
}
