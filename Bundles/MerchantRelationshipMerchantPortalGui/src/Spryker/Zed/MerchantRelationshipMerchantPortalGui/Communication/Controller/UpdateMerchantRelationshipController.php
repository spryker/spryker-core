<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\MerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 */
class UpdateMerchantRelationshipController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Merchant Relation is updated';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'To update Merchant Relationship, please resolve all errors.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idMerchantRelationship = $this->castId(
            $request->get(MerchantRelationshipGuiTableConfigurationProvider::PARAM_MERCHANT_RELATIONSHIP_ID),
        );
        $merchantRelationshipTransfer = $this->getFactory()
            ->createMerchantRelationshipReader()
            ->findMerchantRelationshipById($idMerchantRelationship);

        if (!$merchantRelationshipTransfer) {
            throw new NotFoundHttpException(sprintf('Merchant relationship is not found for ID %d.', $idMerchantRelationship));
        }

        $merchantRelationshipFormDataProvider = $this->getFactory()->createMerchantRelationshipFormDataProvider();
        $merchantRelationshipForm = $this->getFactory()->createMerchantRelationshipForm(
            $merchantRelationshipTransfer,
            $merchantRelationshipFormDataProvider->getOptions($merchantRelationshipTransfer),
        );
        $merchantRelationshipForm->handleRequest($request);

        if (!$merchantRelationshipForm->isSubmitted()) {
            return $this->jsonResponse(
                $this->getResponseData($merchantRelationshipForm, $merchantRelationshipTransfer),
            );
        }

        return $this->handleMerchantRelationshipFormSubmit($merchantRelationshipForm, $merchantRelationshipTransfer);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipForm
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function handleMerchantRelationshipFormSubmit(
        FormInterface $merchantRelationshipForm,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): JsonResponse {
        if (!$merchantRelationshipForm->isValid()) {
            $responseData = $this->getFactory()
                ->createMerchantRelationshipResponseBuilder()
                ->addErrorResponseDataToResponse(
                    $this->getResponseData($merchantRelationshipForm, $merchantRelationshipTransfer),
                    static::RESPONSE_NOTIFICATION_MESSAGE_ERROR,
                );

            return $this->jsonResponse($responseData);
        }

        $merchantRelationshipResponseTransfer = $this->getFactory()
            ->createMerchantRelationshipUpdater()
            ->updateMerchantRelationship($merchantRelationshipForm->getData());

        return $this->handleUpdateMerchantRelationshipResponse(
            $merchantRelationshipForm,
            $merchantRelationshipTransfer,
            $merchantRelationshipResponseTransfer,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipForm
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function handleUpdateMerchantRelationshipResponse(
        FormInterface $merchantRelationshipForm,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer
    ): JsonResponse {
        $responseData = $this->getResponseData($merchantRelationshipForm, $merchantRelationshipTransfer);

        if (!$merchantRelationshipResponseTransfer->getIsSuccessfulOrFail()) {
            $responseData = $this->getFactory()
                ->createMerchantRelationshipResponseBuilder()
                ->addErrorResponseDataToResponse(
                    $responseData,
                    static::RESPONSE_NOTIFICATION_MESSAGE_ERROR,
                    $merchantRelationshipResponseTransfer->getErrors(),
                );

            return $this->jsonResponse($responseData);
        }

        $responseData = $this->getFactory()
            ->createMerchantRelationshipResponseBuilder()
            ->addSuccessfulResponseDataToResponse(
                $responseData,
                static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS,
            );

        return $this->jsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipForm
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return array<string, mixed>
     */
    protected function getResponseData(
        FormInterface $merchantRelationshipForm,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): array {
        $companyBusinessAddressesGroupedByIdCompanyBusinessUnit = $this->getFactory()
            ->createCompanyBusinessUnitAddressGrouper()
            ->getCompanyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit(
                $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail(),
            );

        return [
            'form' => $this->renderView('@MerchantRelationshipMerchantPortalGui/Partials/merchant_relationship_form.twig', [
                'form' => $merchantRelationshipForm->createView(),
                'deleteForm' => $this->getFactory()->createMerchantRelationshipDeleteForm()->createView(),
                'merchantRelationship' => $merchantRelationshipTransfer,
                'companyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit' => $companyBusinessAddressesGroupedByIdCompanyBusinessUnit,
            ])->getContent(),
        ];
    }
}
