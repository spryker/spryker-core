<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\CreateSspInquiryPermissionPlugin;
use SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class InquiryController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SSP_INQUIRY_CANCELED = 'self_service_portal.inquiry.success.canceled';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SSP_INQUIRY_STATUS_CHANGE_ERROR = 'self_service_portal.inquiry.error.status_change';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sspInquiryReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(Request $request, string $sspInquiryReference): RedirectResponse
    {
        if (!$this->getFactory()->getConfig()->getSspInquiryCancelStateMachineEventName()) {
            throw new NotFoundHttpException();
        }

        $sspInquiryCancelForm = $this->getFactory()->getSspInquiryCancelForm();
        $sspInquiryCancelForm->handleRequest($request);

        $response = $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_DETAILS, [
            'reference' => $sspInquiryReference,
        ]);

        if (!$sspInquiryCancelForm->isSubmitted() || !$sspInquiryCancelForm->isValid()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SSP_INQUIRY_STATUS_CHANGE_ERROR);

            return $response;
        }

        /**
         * @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
         */
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        $sspInquiryCollectionResponseTransfer = $this->getClient()->cancelSspInquiryCollection(
            (new SspInquiryCollectionRequestTransfer())
                ->setSspInquiryConditions(
                    (new SspInquiryConditionsTransfer())
                        ->setSspInquiryOwnerConditionGroup(
                            (new SspInquiryOwnerConditionGroupTransfer())
                                ->setIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit())
                                ->setIdCompany($companyUserTransfer->getFkCompany()),
                        ),
                )
                ->addSspInquiry(
                    (new SspInquiryTransfer())
                        ->setReference($sspInquiryReference),
                ),
        );

        if (count($sspInquiryCollectionResponseTransfer->getErrors())) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SSP_INQUIRY_STATUS_CHANGE_ERROR);

            return $response;
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_SSP_INQUIRY_CANCELED);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailAction(Request $request): View|RedirectResponse
    {
        $sspInquiryReference = $request->query->get('reference');
        if (!$sspInquiryReference) {
            return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_LIST);
        }
        $viewData = $this->executeViewAction((string)$sspInquiryReference);

        return $this->view(
            $viewData,
            [],
            '@SelfServicePortal/views/inquiry-detail/inquiry-detail.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request): View|RedirectResponse
    {
        if (!$this->can(CreateSspInquiryPermissionPlugin::KEY)) {
            throw new AccessDeniedHttpException('self_service_portal.inquiry.access.denied');
        }

        return $this->executeCreateAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request): View|RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        $sspInquiryForm = $this
            ->getFactory()
            ->getSspInquiryForm(
                $this->getFactory()->getSspInquiryFormDataProvider()->getOptions(),
            )
            ->handleRequest($request);

        if ($sspInquiryForm->isSubmitted() && $sspInquiryForm->isValid()) {
            $sspInquiryCollectionTransfer = $this->getClient()->createSspInquiryCollection(
                (new SspInquiryCollectionRequestTransfer())->setCompanyUser($companyUserTransfer)->addSspInquiry(
                    $this->getFactory()->createCreateSspInquiryFormDataToTransferMapper()->mapSspInquiryData($sspInquiryForm->getData()),
                ),
            );

            if (!$sspInquiryCollectionTransfer->getErrors()->count()) {
                $this->addSuccessMessage('self_service_portal.inquiry.success.created');

                return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_DETAILS, [
                    'reference' => $sspInquiryCollectionTransfer->getSspInquiries()->getIterator()->current()->getReference(),
                ]);
            }

            $this->addErrors($sspInquiryCollectionTransfer);
        }

        $backUrlType = (string)$request->query->get('backUrlType');

        return $this->view(
            [
                'form' => $sspInquiryForm->createView(),
                'backUrlPath' => $backUrlType ? $this->getFactory()->getConfig()->getInquiryBackUrlTypeToPathMap()[$backUrlType] ?? null : null,
                'backUrlParams' => $backUrlType ? [$this->getFactory()->getConfig()->getInquiryBackUrlTypeToIdentifierMap()[$backUrlType] => $request->query->get('backUrlIdentifier')] : [],
            ],
            [],
            '@SelfServicePortal/views/inquiry-create/inquiry-create.twig',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer
     *
     * @return void
     */
    protected function addErrors(SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer): void
    {
        foreach ($sspInquiryCollectionResponseTransfer->getErrors() as $error) {
            $this->addErrorMessage($error->getMessageOrFail());
        }
    }

    /**
     * @param string $sspInquiryReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    public function executeViewAction(string $sspInquiryReference): array
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('self_service_portal.inquiry.error.company_user_not_found');
        }

        $sspInquiryTransfer = $this->getFactory()->createSspInquiryReader()->getSspInquiry($sspInquiryReference, $companyUserTransfer);

        if (!$sspInquiryTransfer) {
            throw new NotFoundHttpException(sprintf(
                'Ssp Inquiry with provided Reference %s was not found.',
                $sspInquiryReference,
            ));
        }

        return [
            'sspInquiry' => $sspInquiryTransfer,
            'isSspInquiryOwner' => $sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUser() === $companyUserTransfer->getIdCompanyUser(),
            'cancelForm' => $this->getFactory()->getSspInquiryCancelForm()->createView(),
        ];
    }
}
