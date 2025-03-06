<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class ListController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, string|\Symfony\Component\Form\FormView>
     */
    public function indexAction(Request $request): array
    {
        $fileAttachmentFileTableCriteriaTransfer = $this
            ->createFileAttachmentFileTableCriteriaTransfer($request);

        $fileTableFilterForm = $this->getFactory()
            ->createFileTableFilterForm($fileAttachmentFileTableCriteriaTransfer);

        $fileTable = $this->getFactory()
            ->createFileTable($fileAttachmentFileTableCriteriaTransfer);

        return [
            'fileTable' => $fileTable->render(),
            'fileTableFilterForm' => $fileTableFilterForm->createView(),
            'urlSspFileManagementAddFile' => static::URL_SSP_FILE_MANAGEMENT_ADD_FILE,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $fileAttachmentFileTableCriteriaTransfer = $this
            ->createFileAttachmentFileTableCriteriaTransfer($request);

        $fileTable = $this->getFactory()
            ->createFileTable($fileAttachmentFileTableCriteriaTransfer);

        return $this->jsonResponse($fileTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer
     */
    protected function createFileAttachmentFileTableCriteriaTransfer(
        Request $request
    ): FileAttachmentFileTableCriteriaTransfer {
        return (new FileAttachmentFileTableCriteriaTransfer())
            ->fromArray($request->query->all(), true);
    }
}
