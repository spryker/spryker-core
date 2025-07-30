<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\MerchantFileConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Communication\FileImportMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 */
class DownloadController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_UUID_MERCHANT_FILE = 'uuidMerchantFile';

    /**
     * @var string
     */
    protected const PARAM_UUID_MERCHANT_FILE_IMPORT = 'uuidMerchantFileImport';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_REQUIRED_PARAM = 'Missing required parameter "%s"';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_SOURCE_FILE_NOT_FOUND = 'Source file not found';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_IMPORT_NOT_FOUND = 'File import not found';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_IMPORT_NO_ERRORS = 'File import has no errors';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_GENERIC_DOWNLOAD_SOURCE_FILE = 'Cannot download the source file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sourceFileAction(Request $request): Response
    {
        $uuidMerchantFile = $request->get(static::PARAM_UUID_MERCHANT_FILE);

        if (!$uuidMerchantFile) {
            throw new BadRequestHttpException(sprintf(
                static::ERROR_MESSAGE_MISSING_REQUIRED_PARAM,
                static::PARAM_UUID_MERCHANT_FILE,
            ));
        }

        $merchantFileCriteriaTransfer = $this->createMerchantFileCriteriaTransfer($uuidMerchantFile);

        $merchantFileTransfer = $this->getFactory()
            ->getMerchantFileFacade()
            ->findMerchantFile($merchantFileCriteriaTransfer);

        if (!$merchantFileTransfer) {
            throw new NotFoundHttpException(static::ERROR_MESSAGE_SOURCE_FILE_NOT_FOUND);
        }

        try {
            $merchantFileStream = $this->getFactory()
                ->getMerchantFileFacade()
                ->readMerchantFileStream($merchantFileCriteriaTransfer);
        } catch (Exception) {
            throw new UnprocessableEntityHttpException(static::ERROR_MESSAGE_GENERIC_DOWNLOAD_SOURCE_FILE);
        }

        return $this->streamedResponse(
            static function () use ($merchantFileStream): void {
                /** @var resource $outputStream */
                $outputStream = fopen('php://output', 'w');

                stream_copy_to_stream($merchantFileStream, $outputStream);
            },
            Response::HTTP_OK,
            $this->getSourceFileResponseHeaders($merchantFileTransfer),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function errorsFileAction(Request $request): Response
    {
        $merchantFileImportTransfer = $this->getMerchantFileImport($request);

        if (!$merchantFileImportTransfer->getErrors()) {
            throw new NotFoundHttpException(static::ERROR_MESSAGE_FILE_IMPORT_NO_ERRORS);
        }

        $errors = json_decode($merchantFileImportTransfer->getErrors(), true);

        return $this->streamedResponse(
            fn () => $this->streamErrorsFile($errors),
            Response::HTTP_OK,
            $this->getErrorsFileResponseHeaders($this->generateErrorsFileName($merchantFileImportTransfer)),
        );
    }

    /**
     * @param array<array<string, string>> $errors
     *
     * @return void
     */
    protected function streamErrorsFile(array $errors): void
    {
        /** @var resource $outputStream */
        $outputStream = fopen('php://output', 'w');

        fputcsv($outputStream, ['row_number', 'identifier', 'message']);

        try {
            foreach ($errors as $error) {
                fputcsv(
                    $outputStream,
                    [
                        $error['row_number'] ?? '',
                        $error['identifier'] ?? '',
                        $error['message'] ?? '',
                    ],
                );
            }
        } finally {
            fclose($outputStream);
        }
    }

    /**
     * @param string $fileName
     *
     * @return array<string, string>
     */
    protected function getErrorsFileResponseHeaders(string $fileName): array
    {
        return [
            'Content-Disposition' => HeaderUtils::makeDisposition(HeaderUtils::DISPOSITION_ATTACHMENT, $fileName),
            'Content-Type' => 'application/csv',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return array<string, string>
     */
    protected function getSourceFileResponseHeaders(MerchantFileTransfer $merchantFileTransfer): array
    {
        return [
            'Content-Disposition' => HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $merchantFileTransfer->getOriginalFileNameOrFail(),
            ),
            'Content-Type' => $merchantFileTransfer->getContentTypeOrFail(),
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return string
     */
    protected function generateErrorsFileName(MerchantFileImportTransfer $merchantFileImportTransfer): string
    {
        $merchantFile = $merchantFileImportTransfer->getMerchantFileOrFail();

        return sprintf(
            'errors_%s.csv',
            pathinfo($merchantFile->getOriginalFileNameOrFail(), PATHINFO_FILENAME),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    protected function getMerchantFileImport(Request $request): MerchantFileImportTransfer
    {
        $uuidMerchantFileImport = $request->get(static::PARAM_UUID_MERCHANT_FILE_IMPORT);

        if (!$uuidMerchantFileImport) {
            throw new BadRequestHttpException(sprintf(
                static::ERROR_MESSAGE_MISSING_REQUIRED_PARAM,
                static::PARAM_UUID_MERCHANT_FILE_IMPORT,
            ));
        }

        $merchantFileImportTransfer = $this->getFacade()->findMerchantFileImport(
            $this->createMerchantFileImportCriteriaTransfer($uuidMerchantFileImport),
        );

        if (!$merchantFileImportTransfer) {
            throw new NotFoundHttpException(static::ERROR_MESSAGE_FILE_IMPORT_NOT_FOUND);
        }

        return $merchantFileImportTransfer;
    }

    /**
     * @param string $uuidMerchantFileImport
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer
     */
    protected function createMerchantFileImportCriteriaTransfer(
        string $uuidMerchantFileImport
    ): MerchantFileImportCriteriaTransfer {
        $merchantFileImportConditionsTransfer = (new MerchantFileImportConditionsTransfer())
            ->addUuid($uuidMerchantFileImport);

        return (new MerchantFileImportCriteriaTransfer())
            ->setMerchantFileImportConditions($merchantFileImportConditionsTransfer);
    }

    /**
     * @param string $uuidMerchantFile
     *
     * @return \Generated\Shared\Transfer\MerchantFileCriteriaTransfer
     */
    protected function createMerchantFileCriteriaTransfer(string $uuidMerchantFile): MerchantFileCriteriaTransfer
    {
        $merchantFileConditions = (new MerchantFileConditionsTransfer())
            ->addUuid($uuidMerchantFile);

        return (new MerchantFileCriteriaTransfer())
            ->setMerchantFileConditions($merchantFileConditions);
    }
}
