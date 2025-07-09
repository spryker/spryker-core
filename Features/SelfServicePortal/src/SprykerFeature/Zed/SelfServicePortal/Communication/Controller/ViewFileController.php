<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentViewDetailTableCriteriaTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ViewFileController extends FileAbstractController
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

            return $this->redirectResponse(static::URL_PATH_LIST_FILE);
        }

        $fileAttachmentViewDetailTableCriteriaTransfer = $this
            ->createFileAttachmentViewDetailTableCriteriaTransfer($request, $idFile);

        $viewFileDetailTableFilterForm = $this->getFactory()
            ->createViewFileDetailTableFilterForm($fileAttachmentViewDetailTableCriteriaTransfer);

        $viewFileDetailTable = $this->getFactory()
            ->createViewFileDetailTable($idFile, $fileAttachmentViewDetailTableCriteriaTransfer)
            ->render();

        return $this->viewResponse([
            'file' => $fileTransfer,
            'unlinkFileForm' => $this->getFactory()->createUnlinkFileForm()->createView(),
            'deleteFileForm' => $this->getFactory()->createDeleteFileForm()->createView(),
            'viewFileDetailTableFilterForm' => $viewFileDetailTableFilterForm->createView(),
            'viewFileDetailTable' => $viewFileDetailTable,
            'urlPathListFile' => static::URL_PATH_LIST_FILE,
            'urlPathDeleteFile' => static::URL_PATH_DELETE_FILE,
            'urlPathAttachFile' => static::URL_PATH_ATTACH_FILE,
            'urlPathUnlinkFile' => static::URL_PATH_UNLINK_FILE,
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

        $fileAttachmentViewDetailTableCriteriaTransfer = $this
            ->createFileAttachmentViewDetailTableCriteriaTransfer($request, $idFile);

        $viewFileDetailTable = $this->getFactory()
            ->createViewFileDetailTable($idFile, $fileAttachmentViewDetailTableCriteriaTransfer);

        return $this->jsonResponse($viewFileDetailTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentViewDetailTableCriteriaTransfer
     */
    protected function createFileAttachmentViewDetailTableCriteriaTransfer(
        Request $request,
        int $idFile
    ): FileAttachmentViewDetailTableCriteriaTransfer {
        return (new FileAttachmentViewDetailTableCriteriaTransfer())
            ->setIdFile($idFile)
            ->fromArray($request->query->all(), true);
    }
}
