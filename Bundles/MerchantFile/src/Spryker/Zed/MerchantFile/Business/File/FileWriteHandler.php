<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\File;

use Exception;
use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MerchantFile\Business\File\Validation\MerchantFileValidatorInterface;
use Spryker\Zed\MerchantFile\Business\File\Writer\FileWriterInterface;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Expander\MerchantFileExpanderInterface;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Writer\MerchantFileWriterInterface;

class FileWriteHandler implements FileWriteHandlerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const GENERIC_ERROR_MESSAGE = 'An error occurred during file upload. Please try again later.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_TYPE = 'error';

    /**
     * @param \Spryker\Zed\MerchantFile\Business\File\Validation\MerchantFileValidatorInterface $validationExecutor
     * @param \Spryker\Zed\MerchantFile\Business\MerchantFile\Writer\MerchantFileWriterInterface $merchantFileWriter
     * @param \Spryker\Zed\MerchantFile\Business\MerchantFile\Expander\MerchantFileExpanderInterface $merchantFileExpander
     * @param \Spryker\Zed\MerchantFile\Business\File\Writer\FileWriterInterface $fileWriter
     */
    public function __construct(
        protected MerchantFileValidatorInterface $validationExecutor,
        protected MerchantFileWriterInterface $merchantFileWriter,
        protected MerchantFileExpanderInterface $merchantFileExpander,
        protected FileWriterInterface $fileWriter
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function writeMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileResultTransfer
    {
        $merchantFileResultTransfer = $this->validationExecutor->validateMerchantFile($merchantFileTransfer);
        if (!$merchantFileResultTransfer->getIsSuccessful()) {
            return $merchantFileResultTransfer->setMerchantFile($merchantFileTransfer);
        }

        try {
            $merchantFileTransfer = $this->merchantFileExpander->expandWithMerchantUser($merchantFileTransfer);
            $merchantFileTransfer = $this->fileWriter->writeMerchantFile($merchantFileTransfer);
            $merchantFileTransfer = $this->merchantFileWriter->saveMerchantFile($merchantFileTransfer);

            return $merchantFileResultTransfer->setMerchantFile($merchantFileTransfer);
        } catch (Exception $exception) {
            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);

            return $merchantFileResultTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createGenericErrorMessage());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createGenericErrorMessage(): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_ERROR_TYPE)
            ->setValue(static::GENERIC_ERROR_MESSAGE);
    }
}
