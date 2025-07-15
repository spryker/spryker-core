<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ListInquiryController extends AbstractController
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
            '@SelfServicePortal/views/list-inquiry/list-inquiry.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(Request $request): array
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('self_service_portal.inquiry.error.company_user_not_found');
        }

        $sspInquirySearchForm = $this->getFactory()->getSspInquirySearchForm(
            $this->getFactory()->getSspInquirySearchFormDataProvider()->getOptions(),
        );

        $sspInquiryConditionsTransfer = (new SspInquiryConditionsTransfer())
            ->setSspInquiryOwnerConditionGroup(new SspInquiryOwnerConditionGroupTransfer());

        $sspInquiryCriteriaTransfer = (new SspInquiryCriteriaTransfer())
            ->setSspInquiryConditions($sspInquiryConditionsTransfer)
            ->setInclude(
                (new SspInquiryIncludeTransfer())
                    ->setWithCompanyUser(true),
            );

        $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setCompanyUser($companyUserTransfer);
        $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setIdCompany($companyUserTransfer->getFkCompany());
        $sspInquiryConditionsTransfer->getSspInquiryOwnerConditionGroupOrFail()->setIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());

        $sspInquirySearchForm->handleRequest($request);

        $sspInquiryCriteriaTransfer = $this->getFactory()
            ->createSspInquirySearchFormHandler()
            ->handleFormSubmit($sspInquirySearchForm, $sspInquiryCriteriaTransfer);

        if ($request->query->has(static::QUERY_PARAM_SSP_ASSET_REFERENCE)) {
            $sspInquiryConditionsTransfer->addSspAssetReference((string)$request->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE));
        }

        $sspInquiryCollectionTransfer = $this->getFactory()->createSspInquiryReader()->getSspInquiryCollection(
            $request,
            $sspInquiryCriteriaTransfer,
        );

        return [
            'pagination' => $sspInquiryCollectionTransfer->getPagination(),
            'sspInquiryList' => $sspInquiryCollectionTransfer->getSspInquiries(),
            'sspInquirySearchForm' => $sspInquirySearchForm->createView(),
        ];
    }
}
