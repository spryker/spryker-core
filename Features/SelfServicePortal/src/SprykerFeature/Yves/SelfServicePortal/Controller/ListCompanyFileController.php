<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterSubForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ListCompanyFileController extends AbstractController
{
    /**
     * @var string
     */
    protected const QUERY_PARAM_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

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
            '@SelfServicePortal/views/list-company-file/list-company-file.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(Request $request): array
    {
        $fileSearchFilterForm = $this->getFactory()->createFileSearchFilterForm(
            [
                FileSearchFilterForm::FIELD_FILTERS =>
                [
                    FileSearchFilterSubForm::FIELD_SEARCH => $request->query->has(static::QUERY_PARAM_SSP_ASSET_REFERENCE) ? $request->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE) : null,
                ],
            ],
            $this->getLocale(),
        );
        $fileSearchFilterHandler = $this->getFactory()->createFileSearchFilterHandler();
        $fileAttachmentFileCollectionTransfer = $fileSearchFilterHandler->handleSearchFormSubmit($request, $fileSearchFilterForm);

        return [
            'fileAttachments' => $fileAttachmentFileCollectionTransfer->getFileAttachments(),
            'pagination' => $fileAttachmentFileCollectionTransfer->getPagination(),
            'fileSearchFilterForm' => $fileSearchFilterForm->createView(),
        ];
    }
}
