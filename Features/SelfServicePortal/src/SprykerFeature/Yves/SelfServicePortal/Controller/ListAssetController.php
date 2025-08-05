<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetSearchForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ListAssetController extends AbstractController
{
    public function indexAction(Request $request): View
    {
        return $this->view(
            $this->executeIndexAction($request),
            [],
            '@SelfServicePortal/views/list-asset/list-asset.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(Request $request): array
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('company.error.company_user_not_found');
        }

        if (!$this->getFactory()->createSspAssetCustomerPermissionChecker()->canViewAsset()) {
            throw new AccessDeniedHttpException('self_service_portal.asset.access.denied');
        }

        $sspAssetSearchForm = $this->getFactory()->createSspAssetSearchForm(
            $this->getFactory()->createSspAssetSearchFormDataProvider()->getOptions(),
        );

        $data = $request->query->all()[SspAssetSearchForm::FORM_NAME] ?? [];
        $isReset = $data[SspAssetSearchForm::FIELD_RESET] ?? null;

        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();

        if (!$isReset) {
            $sspAssetSearchForm->handleRequest($request);

            $sspAssetCriteriaTransfer = $this->getFactory()->createSspAssetSearchFormHandler()->handleSearchForm($sspAssetSearchForm, $sspAssetCriteriaTransfer, $companyUserTransfer);
        }

        $sspAssetCollectionTransfer = $this->getFactory()
            ->createSspAssetReader()
            ->getSspAssetCollection($request, $sspAssetCriteriaTransfer, $companyUserTransfer);

        return [
            'pagination' => $sspAssetCollectionTransfer->getPagination(),
            'sspAssetList' => $sspAssetCollectionTransfer->getSspAssets(),
            'sspAssetSearchForm' => $sspAssetSearchForm->createView(),
        ];
    }
}
