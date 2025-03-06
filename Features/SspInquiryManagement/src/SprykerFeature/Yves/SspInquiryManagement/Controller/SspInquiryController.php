<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Controller;

use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\CreateSspInquiryPermissionPlugin;
use SprykerFeature\Yves\SspInquiryManagement\Plugin\Router\SspInquiryRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementClientInterface getClient()
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementFactory getFactory()
 */
class SspInquiryController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SSP_INQUIRY_CANCELED = 'ssp_inquiry.success.canceled';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SSP_INQUIRY_STATUS_CHANGE_ERROR = 'ssp_inquiry.error.status_change';

    /**
     * @var string
     */
    protected const PARAM_SSP_INQUIRY_REFERENCE = 'sspInquiryReference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sspInquiryReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
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

        $response = $this->redirectResponseInternal(SspInquiryRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_DETAILS, [
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

         $sspInquiryTransfer = $this->getFactory()->createSspInquiryReader()->getSspInquiry($sspInquiryReference, $companyUserTransfer);

        if (
            !$sspInquiryTransfer
            || $sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail() !== $companyUserTransfer->getIdCompanyUser()
        ) {
            throw new NotFoundHttpException();
        }

        if (!$this->getFactory()->createSspInquiryCustomerPermissionChecker()->canViewSspInquiry($sspInquiryTransfer, $companyUserTransfer)) {
            throw new AccessDeniedHttpException('ssp_inquiry.access.denied');
        }

         $sspInquiryCollectionResponseTransfer = $this->getClient()->cancelSspInquiryCollection(
             (new SspInquiryCollectionRequestTransfer())
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
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function listAction(Request $request): View|RedirectResponse
    {
        $viewData = $this->executeListAction($request);

        return $this->view(
            $viewData,
            [],
            '@SspInquiryManagement/views/list/list.twig',
        );
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
            return $this->redirectResponseInternal(SspInquiryRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_LIST);
        }
        $viewData = $this->executeViewAction((string)$sspInquiryReference);

        return $this->view(
            $viewData,
            [],
            '@SspInquiryManagement/views/detail/detail.twig',
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
            throw new AccessDeniedHttpException('ssp_inquiry.access.denied');
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
         $sspInquiryForm = $this
            ->getFactory()
            ->getSspInquiryForm(
                $this->getFactory()->getSspInquiryFormDataProvider()->getOptions(),
            )
            ->handleRequest($request);

        if ($sspInquiryForm->isSubmitted() && $sspInquiryForm->isValid()) {
             $sspInquiryCollectionTransfer = $this->getFactory()->getSspInquiryClient()->createSspInquiryCollection(
                 (new SspInquiryCollectionRequestTransfer())->addSspInquiry(
                     $this->getFactory()->createCreateSspInquiryFormDataToTransferMapper()->mapSspInquiryData($sspInquiryForm->getData()),
                 ),
             );

            if (!$sspInquiryCollectionTransfer->getErrors()->count()) {
                $this->addSuccessMessage('ssp_inquiry.success.created');

                return $this->redirectResponseInternal(SspInquiryRouteProviderPlugin::ROUTE_NAME_SSP_INQUIRY_DETAILS, [
                    'reference' => $sspInquiryCollectionTransfer->getSspInquiries()->getIterator()->current()->getReference(),
                ]);
            }

            $this->addErrors($sspInquiryCollectionTransfer);
        }

        $backUrlType = (string)$request->query->get('backUrlType');

        return $this->view(
            [
                'form' => $sspInquiryForm->createView(),
                'backUrlPath' => $backUrlType ? $this->getFactory()->getConfig()->getBackUrlPath($backUrlType) : null,
                'backUrlParams' => $backUrlType ? [$this->getFactory()->getConfig()->getBackUrlIdentifier($backUrlType) => $request->query->get('backUrlIdentifier')] : [],
            ],
            [],
            '@SspInquiryManagement/views/create/create.twig',
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    protected function executeListAction(Request $request): array
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('ssp_inquiry.error.company_user_not_found');
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

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('ssp_inquiry.error.company_user_not_found');
        }

         $sspInquiryConditionsTransfer = $this->getFactory()->createSspInquiryCustomerPermissionExpander()->extendSspInquiryCriteriaTransferWithPermissions(
             $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail(),
             $companyUserTransfer,
         );

         $sspInquiryCriteriaTransfer = $this->handleSspInquirySearchFormSubmit($request, $sspInquirySearchForm, $sspInquiryCriteriaTransfer);

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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $sspInquirySearchForm
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    protected function handleSspInquirySearchFormSubmit(
        Request $request,
        FormInterface $sspInquirySearchForm,
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCriteriaTransfer {
         $sspInquirySearchForm->handleRequest($request);

        return $this->getFactory()
            ->createSspInquirySearchFormHandler()
            ->handleFormSubmit($sspInquirySearchForm, $sspInquiryCriteriaTransfer);
    }

    /**
     * @param string $sspInquiryReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return array<string, mixed>
     */
    public function executeViewAction(string $sspInquiryReference): array
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('ssp_inquiry.error.company_user_not_found');
        }

         $sspInquiryTransfer = $this->getFactory()->createSspInquiryReader()->getSspInquiry($sspInquiryReference, $companyUserTransfer);

        if (!$sspInquiryTransfer) {
            throw new NotFoundHttpException(sprintf(
                'Ssp Inquiry with provided Reference %s was not found.',
                $sspInquiryReference,
            ));
        }

        if (!$this->getFactory()->createSspInquiryCustomerPermissionChecker()->canViewSspInquiry($sspInquiryTransfer, $companyUserTransfer)) {
            throw new AccessDeniedHttpException('ssp_inquiry.access.denied');
        }

        return [
            'sspInquiry' => $sspInquiryTransfer,
            'isSspInquiryOwner' => $sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUser() === $companyUserTransfer->getIdCompanyUser(),
            'cancelForm' => $this->getFactory()->getSspInquiryCancelForm()->createView(),
        ];
    }
}
