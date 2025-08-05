<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentTableCriteriaTransfer;
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
        $fileAttachmentTableCriteriaTransfer = $this
            ->createFileAttachmentTableCriteriaTransfer($request);

        $fileTableFilterForm = $this->getFactory()
            ->createFileTableFilterForm($fileAttachmentTableCriteriaTransfer);

        $fileTable = $this->getFactory()
            ->createFileTable($fileAttachmentTableCriteriaTransfer);

        return [
            'fileTable' => $fileTable->render(),
            'fileTableFilterForm' => $fileTableFilterForm->createView(),
            'urlPathAddFile' => static::URL_PATH_ADD_FILE,
        ];
    }

    public function tableAction(Request $request): JsonResponse
    {
        $fileAttachmentTableCriteriaTransfer = $this
            ->createFileAttachmentTableCriteriaTransfer($request);

        $fileTable = $this->getFactory()
            ->createFileTable($fileAttachmentTableCriteriaTransfer);

        return $this->jsonResponse($fileTable->fetchData());
    }

    protected function createFileAttachmentTableCriteriaTransfer(
        Request $request
    ): FileAttachmentTableCriteriaTransfer {
        return (new FileAttachmentTableCriteriaTransfer())
            ->fromArray($request->query->all(), true);
    }
}
