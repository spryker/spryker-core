<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface;

class FileWriter implements FileWriterInterface
{
    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        protected DataImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
    }

    /**
     * @param mixed|resource $fileStream
     *
     * @return callable
     */
    public function write($fileStream): callable
    {
        return static function () use ($fileStream): void {
            /** @var resource $outputStream */
            $outputStream = fopen('php://output', 'w');

            stream_copy_to_stream($fileStream, $outputStream);
        };
    }

    /**
     * @param array<array<string, string>> $errors
     *
     * @return callable
     */
    public function writeErrors(array $errors): callable
    {
        return function () use ($errors): void {
            /** @var resource $outputStream */
            $outputStream = fopen('php://output', 'w');

            fputcsv($outputStream, [
                $this->translatorFacade->trans('row_number'),
                $this->translatorFacade->trans('identifier'),
                $this->translatorFacade->trans('message'),
            ]);

            try {
                foreach ($errors as $error) {
                    fputcsv(
                        $outputStream,
                        [
                            $error['row_number'] ?? '',
                            $error['identifier'] ?? '',
                            $this->translateMessage($error),
                        ],
                    );
                }
            } finally {
                fclose($outputStream);
            }
        };
    }

    /**
     * @param array<string, mixed> $error
     *
     * @return string
     */
    protected function translateMessage(array $error): string
    {
        if (!isset($error['error'])) {
            return $error['message'] ? $this->translatorFacade->trans($error['message']) : '';
        }

        $errorTransfer = (new ErrorTransfer())->fromArray($error['error'], true);

        return $this->translatorFacade->trans($errorTransfer->getMessageOrFail(), $errorTransfer->getParameters());
    }
}
