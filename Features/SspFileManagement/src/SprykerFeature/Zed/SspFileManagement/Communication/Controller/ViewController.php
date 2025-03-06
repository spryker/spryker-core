<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface getFileManagerFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class ViewController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        $fileTransfer = $this->getFactory()->createFileReader()->findFileByIdFile($idFile);

        if ($fileTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FILE_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idFile,
            ]);

            return $this->redirectResponse(static::URL_SSP_FILE_MANAGEMENT_LIST);
        }

        $fileAttachmentFileViewDetailTableCriteriaTransfer = $this
            ->createFileAttachmentFileViewDetailTableCriteriaTransfer($request, $idFile);

        $fileViewDetailTableFilterForm = $this->getFactory()
            ->createFileViewDetailTableFilterForm($fileAttachmentFileViewDetailTableCriteriaTransfer);

        $fileViewDetailTable = $this->getFactory()
            ->createFileViewDetailTable($idFile, $fileAttachmentFileViewDetailTableCriteriaTransfer)
            ->render();

        return $this->viewResponse([
            'file' => $fileTransfer,
            'unlinkFileForm' => $this->getFactory()->createUnlinkFileForm()->createView(),
            'deleteFileForm' => $this->getFactory()->createDeleteFileForm()->createView(),
            'fileViewDetailTableFilterForm' => $fileViewDetailTableFilterForm->createView(),
            'fileViewDetailTable' => $fileViewDetailTable,
            'urlSspFileManagementList' => static::URL_SSP_FILE_MANAGEMENT_LIST,
            'urlSspFileManagementDelete' => static::URL_SSP_FILE_MANAGEMENT_DELETE,
            'urlSspFileManagementAttach' => static::URL_SSP_FILE_MANAGEMENT_ATTACH,
            'urlSspFileManagementUnlink' => static::URL_SSP_FILE_MANAGEMENT_UNLINK,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        $fileAttachmentFileViewDetailTableCriteriaTransfer = $this
            ->createFileAttachmentFileViewDetailTableCriteriaTransfer($request, $idFile);

        $fileViewDetailTable = $this->getFactory()
            ->createFileViewDetailTable($idFile, $fileAttachmentFileViewDetailTableCriteriaTransfer);

        return $this->jsonResponse($fileViewDetailTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer
     */
    protected function createFileAttachmentFileViewDetailTableCriteriaTransfer(
        Request $request,
        int $idFile
    ): FileAttachmentFileViewDetailTableCriteriaTransfer {
        return (new FileAttachmentFileViewDetailTableCriteriaTransfer())
            ->setIdFile($idFile)
            ->fromArray($request->query->all(), true);
    }
}
