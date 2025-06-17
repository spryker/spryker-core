<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ListFileController extends FileAbstractController
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
            'urlPathAddFile' => static::URL_PATH_ADD_FILE,
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
