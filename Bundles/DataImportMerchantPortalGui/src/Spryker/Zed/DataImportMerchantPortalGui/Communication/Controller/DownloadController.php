<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\Communication\DataImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class DownloadController extends AbstractController
{
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
    protected const ERROR_MESSAGE_FILE_IMPORT_NO_ERRORS = 'File import has no errors';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_GENERIC_DOWNLOAD_SOURCE_FILE = 'Cannot download the source file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sourceFileAction(Request $request): Response
    {
        $dataImportMerchantFileTransfer = $this->getDataImportMerchantFile($request);
        $fileReader = $this->getFactory()->createFileReader();

        try {
            $fileStream = $fileReader->read($dataImportMerchantFileTransfer);
        } catch (Exception) {
            throw new UnprocessableEntityHttpException(static::ERROR_MESSAGE_GENERIC_DOWNLOAD_SOURCE_FILE);
        }

        return $this->streamedResponse(
            $this->getFactory()->createFileWriter()->write($fileStream),
            Response::HTTP_OK,
            $fileReader->getSourceFileResponseHeaders($dataImportMerchantFileTransfer),
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
        $dataImportMerchantFileTransfer = $this->getDataImportMerchantFile($request);
        $importResult = $dataImportMerchantFileTransfer->getImportResultOrFail();
        $errorsJson = $importResult->getErrors();

        if (!$errorsJson) {
            throw new NotFoundHttpException(static::ERROR_MESSAGE_FILE_IMPORT_NO_ERRORS);
        }

        $errors = $this->getFactory()->getUtilEncodingService()->decodeJson($errorsJson, true) ?: [];

        return $this->streamedResponse(
            $this->getFactory()->createFileWriter()->writeErrors($errors),
            Response::HTTP_OK,
            $this->getFactory()->createFileReader()->getErrorsFileResponseHeaders($dataImportMerchantFileTransfer),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    protected function getDataImportMerchantFile(Request $request): DataImportMerchantFileTransfer
    {
        $uuid = $request->get(DataImportMerchantFileTransfer::UUID);
        if (!$uuid) {
            throw new BadRequestHttpException(sprintf(
                static::ERROR_MESSAGE_MISSING_REQUIRED_PARAM,
                DataImportMerchantFileTransfer::UUID,
            ));
        }

        $dataImportMerchantFileTransfer = $this->getFactory()
            ->createDataImportMerchantFileReader()
            ->findDataImportMerchantFileByUuid($uuid);

        if (!$dataImportMerchantFileTransfer) {
            throw new NotFoundHttpException(static::ERROR_MESSAGE_SOURCE_FILE_NOT_FOUND);
        }

        return $dataImportMerchantFileTransfer;
    }
}
