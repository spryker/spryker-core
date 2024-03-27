<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\MerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationRequestGuiTableConfigurationProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 */
class UpdateMerchantRelationRequestController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_LIST_MERCHANT_RELATIONSHIP = '/merchant-relation-merchant-portal-gui/list-merchant-relationship';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_REJECTED = 'rejected';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idMerchantRelationRequest = $this->castId(
            $request->get(MerchantRelationRequestGuiTableConfigurationProvider::PARAM_MERCHANT_RELATION_REQUEST_ID),
        );
        $merchantRelationRequestTransfer = $this->getFactory()
            ->createMerchantRelationRequestReader()
            ->findCurrentMerchantUserMerchantRelationRequestByIdMerchantRelationRequest($idMerchantRelationRequest);

        if (!$merchantRelationRequestTransfer) {
            throw new NotFoundHttpException(sprintf('Merchant relation request is not found for ID %d.', $idMerchantRelationRequest));
        }

        $merchantRelationRequestForm = $this->getFactory()->createMerchantRelationRequestForm(
            $merchantRelationRequestTransfer,
            $this->getFactory()->createMerchantRelationRequestFormDataProvider()->getOptions($merchantRelationRequestTransfer),
        );
        $merchantRelationRequestForm->handleRequest($request);

        if (!$merchantRelationRequestForm->isSubmitted()) {
            return $this->getResponse($merchantRelationRequestForm, $merchantRelationRequestTransfer);
        }

        return $this->executeMerchantRelationRequestFormSubmission(
            $merchantRelationRequestForm,
            $merchantRelationRequestTransfer,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function executeMerchantRelationRequestFormSubmission(
        FormInterface $merchantRelationRequestForm,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): JsonResponse {
        if (!$merchantRelationRequestForm->isValid()) {
            return $this->getResponse($merchantRelationRequestForm, $merchantRelationRequestTransfer, false);
        }

        $clickedButtonName = $this->findClickedButtonName($merchantRelationRequestForm);
        $formDataMerchantRelationRequestTransfer = $merchantRelationRequestForm->getData();

        if ($clickedButtonName === MerchantRelationRequestForm::FIELD_APPROVE) {
            $formDataMerchantRelationRequestTransfer->setStatus(static::STATUS_APPROVED);
        }

        if ($clickedButtonName === MerchantRelationRequestForm::FIELD_REJECT) {
            $formDataMerchantRelationRequestTransfer->setStatus(static::STATUS_REJECTED);
        }

        $merchantRelationRequestCollectionResponseTransfer = $this->getFactory()
            ->createMerchantRelationRequestUpdater()
            ->updateMerchantRelationRequest($formDataMerchantRelationRequestTransfer);

        if ($merchantRelationRequestCollectionResponseTransfer->getErrors()->count()) {
            return $this->getResponse(
                $merchantRelationRequestForm,
                $merchantRelationRequestTransfer,
                false,
                $merchantRelationRequestCollectionResponseTransfer,
            );
        }

        return $this->getResponse($merchantRelationRequestForm, $merchantRelationRequestTransfer, true);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param bool|null $isSuccess
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer|null $merchantRelationRequestCollectionResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $merchantRelationRequestForm,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        ?bool $isSuccess = null,
        ?MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer = null
    ): JsonResponse {
        $companyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit = $this->getFactory()
            ->createCompanyBusinessUnitAddressBuilder()
            ->buildCompanyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit(
                $merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits(),
            );

        $parameters = [
            'form' => $merchantRelationRequestForm->createView(),
            'merchantRelationRequest' => $merchantRelationRequestTransfer,
            'companyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit' => $companyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit,
            'actionConfiguration' => $this->getFactory()
                ->createMerchantRelationRequestFormActionConfigurationProvider()
                ->getActions($merchantRelationRequestTransfer),
            'isEditableMerchantRelationRequest' => $this->isEditableMerchantRelationRequest($merchantRelationRequestTransfer),
        ];

        if ($merchantRelationRequestTransfer->getStatus() === static::STATUS_APPROVED) {
            $parameters['urlListMerchantRelationship'] = $this->getFactory()
                ->createMerchantRelationTableUrlBuilder()
                ->buildMerchantRelationTableUrl($merchantRelationRequestTransfer);
        }

        $responseData = [
            'form' => $this->renderView(
                '@MerchantRelationRequestMerchantPortalGui/Partials/merchant_relation_request_form.twig',
                $parameters,
            )->getContent(),
        ];

        if ($isSuccess === true) {
            $responseData = $this->getFactory()
                ->createUpdateMerchantRelationRequestResponseBuilder()
                ->addSuccessResponseDataToResponse($responseData);
        }

        if ($isSuccess === false) {
            $responseData = $this->getFactory()
                ->createUpdateMerchantRelationRequestResponseBuilder()->addErrorResponseDataToResponse(
                    $responseData,
                    $merchantRelationRequestForm,
                    $merchantRelationRequestCollectionResponseTransfer,
                );
        }

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     *
     * @return string|null
     */
    protected function findClickedButtonName(FormInterface $merchantRelationRequestForm): ?string
    {
        // @phpstan-ignore-next-line
        $clickedButton = $merchantRelationRequestForm->getClickedButton();

        if ($clickedButton !== null) {
            return $clickedButton->getName();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isEditableMerchantRelationRequest(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        return in_array(
            $merchantRelationRequestTransfer->getStatus(),
            $this->getFactory()->getConfig()->getEditableMerchantRelationRequestStatuses(),
            true,
        );
    }
}
