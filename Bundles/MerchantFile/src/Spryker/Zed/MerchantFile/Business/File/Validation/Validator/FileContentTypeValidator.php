<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\File\Validation\Validator;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\MerchantFile\MerchantFileConfig;

class FileContentTypeValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'File content type %contentType% unsupported for file type %fileType%.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_TYPE = 'error';

    /**
     * @var string
     */
    protected const PARAM_CONTENT_TYPE = '%contentType%';

    /**
     * @var string
     */
    protected const PARAM_FILE_TYPE = '%fileType%';

    /**
     * @param \Spryker\Zed\MerchantFile\MerchantFileConfig $config
     */
    public function __construct(protected MerchantFileConfig $config)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     * @param \Generated\Shared\Transfer\MerchantFileResultTransfer $merchantFileResultTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function validate(
        MerchantFileTransfer $merchantFileTransfer,
        MerchantFileResultTransfer $merchantFileResultTransfer
    ): MerchantFileResultTransfer {
        if (!$merchantFileTransfer->getType()) {
            return $merchantFileResultTransfer;
        }

        $contentTypes = $this->getContentTypesByFileType($merchantFileTransfer->getType());
        if (in_array($merchantFileTransfer->getContentType(), $contentTypes, true)) {
            return $merchantFileResultTransfer;
        }

        $merchantFileResultTransfer->setIsSuccessful(false);
        $merchantFileResultTransfer->addMessage(
            $this->createViolationErrorMessage(
                $merchantFileTransfer->getContentTypeOrFail(),
                $merchantFileTransfer->getTypeOrFail(),
            ),
        );

        return $merchantFileResultTransfer;
    }

    /**
     * @param string $fileType
     *
     * @return array<string>
     */
    protected function getContentTypesByFileType(string $fileType): array
    {
        return $this->config->getFileTypeToContentTypeMapping()[$fileType] ?? [];
    }

    /**
     * @param string $contentType
     * @param string $fileType
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createViolationErrorMessage(
        string $contentType,
        string $fileType
    ): MessageTransfer {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_ERROR_TYPE)
            ->setValue(static::ERROR_MESSAGE)
            ->setParameters([
                static::PARAM_CONTENT_TYPE => $contentType,
                static::PARAM_FILE_TYPE => $fileType,
            ]);
    }
}
