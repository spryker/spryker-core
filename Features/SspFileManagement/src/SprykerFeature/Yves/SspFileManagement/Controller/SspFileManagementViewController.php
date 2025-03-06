<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Controller;

use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspFileManagement\SspFileManagementFactory getFactory()
 */
class SspFileManagementViewController extends SspFileManagementAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        return $this->view(
            $this->executeIndexAction($request),
            [],
            '@SspFileManagement/views/ssp-file-management-view/ssp-file-management-view.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(Request $request): array
    {
        $fileSearchFilterForm = $this->getFactory()->createFileSearchFilterForm($this->getLocale());
        $fileSearchFilterHandler = $this->getFactory()->createFileSearchFilterHandler();
        $fileAttachmentFileCollectionTransfer = $fileSearchFilterHandler->handleSearchFormSubmit($request, $fileSearchFilterForm);

        return [
            'files' => $fileAttachmentFileCollectionTransfer->getFiles(),
            'pagination' => $fileAttachmentFileCollectionTransfer->getPagination(),
            'fileSearchFilterForm' => $fileSearchFilterForm->createView(),
        ];
    }
}
