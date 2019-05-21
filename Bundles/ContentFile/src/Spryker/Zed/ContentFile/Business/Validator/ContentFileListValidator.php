<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFile\Business\Validator;

use Generated\Shared\Transfer\ContentFileListTermTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ContentFile\ContentFileConfig;

class ContentFileListValidator implements ContentFileListValidatorInterface
{
    protected const ERROR_MESSAGE_MAX_NUMBER_OF_FILES = 'There are too many files in the list, please reduce the list size to {number} or fewer.';
    protected const ERROR_MESSAGE_PARAMETER_COUNT = '{number}';

    /**
     * @var \Spryker\Zed\ContentFile\ContentFileConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentFile\ContentFileConfig $config
     */
    public function __construct(ContentFileConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentFileListTermTransfer $contentFileListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validate(
        ContentFileListTermTransfer $contentFileListTermTransfer
    ): ContentValidationResponseTransfer {
        $contentValidationResponseTransfer = (new ContentValidationResponseTransfer())
            ->setIsSuccess(true);

        $contentParameterMessageTransfer = $this->validateNumberOfFilesConstraint($contentFileListTermTransfer);

        if ($contentParameterMessageTransfer->getMessages()->count()) {
            $contentValidationResponseTransfer->setIsSuccess(false)
                ->addParameterMessages($contentParameterMessageTransfer);
        }

        return $contentValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentFileListTermTransfer $contentFileListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentParameterMessageTransfer
     */
    protected function validateNumberOfFilesConstraint(
        ContentFileListTermTransfer $contentFileListTermTransfer
    ): ContentParameterMessageTransfer {
        $numberOfFilesInFileList = count($contentFileListTermTransfer->getFileIds());
        $maxFilesInFileList = $this->config->getMaxFilesInFileList();

        if ($numberOfFilesInFileList > $maxFilesInFileList) {
            $message = (new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_MAX_NUMBER_OF_FILES)
                ->setParameters([static::ERROR_MESSAGE_PARAMETER_COUNT => $maxFilesInFileList]);

            return (new ContentParameterMessageTransfer())
                ->setParameter(ContentFileListTermTransfer::FILE_IDS)
                ->addMessage($message);
        }

        return new ContentParameterMessageTransfer();
    }
}
