<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\MerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Table\MerchantRelationRequestListTable;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractMerchantRelationRequestController
{
    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\ApproveMerchantRelationRequestController::indexAction()
     *
     * @var string
     */
    protected const URL_APPROVE_MERCHANT_RELATION_REQUEST = '/merchant-relation-request-gui/approve-merchant-relation-request?%s=%s';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\RejectMerchantRelationRequestController::indexAction()
     *
     * @var string
     */
    protected const URL_REJECT_MERCHANT_RELATION_REQUEST = '/merchant-relation-request-gui/reject-merchant-relation-request?%s=%s';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_LIST_MERCHANT_RELATIONSHIP = '/merchant-relationship-gui/list-merchant-relationship?id-company=%s';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_REQUEST_SAVED = 'Merchant relation request has been successfully saved.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request)
    {
        $merchantRelationRequestTransfer = $this->findMerchantRelationRequestByRequest($request);
        if ($merchantRelationRequestTransfer === null) {
            return $this->redirectResponse(static::URL_MERCHANT_RELATION_REQUEST_LIST);
        }

        $merchantRelationRequestFormDataProvider = $this->getFactory()->createMerchantRelationRequestFormDataProvider();
        $merchantRelationRequestForm = $this->getFactory()->createMerchantRelationRequestForm(
            $merchantRelationRequestTransfer,
            $merchantRelationRequestFormDataProvider->getOptions($merchantRelationRequestTransfer),
        );
        $merchantRelationRequestForm->handleRequest($request);

        if ($merchantRelationRequestForm->isSubmitted()) {
            return $this->handleMerchantRelationRequestFormSubmission($merchantRelationRequestTransfer, $merchantRelationRequestForm);
        }

        return $this->viewResponse($this->getResponseData($merchantRelationRequestTransfer, $merchantRelationRequestForm));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    protected function handleMerchantRelationRequestFormSubmission(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        FormInterface $merchantRelationRequestForm
    ) {
        if (!$merchantRelationRequestForm->isValid()) {
            return $this->viewResponse($this->getResponseData($merchantRelationRequestTransfer, $merchantRelationRequestForm));
        }

        $clickedButtonName = $this->findClickedButtonName($merchantRelationRequestForm);

        $merchantRelationRequestCollectionResponseTransfer = $this->updateMerchantRelationRequest($merchantRelationRequestForm->getData());
        if ($merchantRelationRequestCollectionResponseTransfer->getErrors()->count()) {
            return $this->viewResponse($this->getResponseData($merchantRelationRequestTransfer, $merchantRelationRequestForm));
        }

        if ($clickedButtonName === MerchantRelationRequestForm::BUTTON_APPROVE) {
            return $this->redirectResponse(sprintf(
                static::URL_APPROVE_MERCHANT_RELATION_REQUEST,
                MerchantRelationRequestListTable::PARAM_ID_MERCHANT_RELATION_REQUEST,
                $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail(),
            ));
        }

        if ($clickedButtonName === MerchantRelationRequestForm::BUTTON_REJECT) {
            return $this->redirectResponse(sprintf(
                static::URL_REJECT_MERCHANT_RELATION_REQUEST,
                MerchantRelationRequestListTable::PARAM_ID_MERCHANT_RELATION_REQUEST,
                $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail(),
            ));
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_REQUEST_SAVED);

        return $this->viewResponse($this->getResponseData($merchantRelationRequestTransfer, $merchantRelationRequestForm));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     *
     * @return array<string, mixed>
     */
    protected function getResponseData(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        FormInterface $merchantRelationRequestForm
    ): array {
        $responseData = [
            'merchantRelationRequestForm' => $merchantRelationRequestForm->createView(),
            'merchantRelationRequest' => $merchantRelationRequestTransfer,
            'statusClassLabelMapping' => $this->getFactory()->getConfig()->getStatusClassLabelMapping(),
            'urlMerchantRelationRequestList' => static::URL_MERCHANT_RELATION_REQUEST_LIST,
            'isEditableMerchantRelationRequest' => $this->isEditableMerchantRelationRequest($merchantRelationRequestTransfer),
        ];

        if ($merchantRelationRequestTransfer->getStatus() === static::STATUS_APPROVED) {
            $responseData['urlListMerchantRelationship'] = sprintf(
                static::URL_LIST_MERCHANT_RELATIONSHIP,
                $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail()->getIdCompanyOrFail(),
            );
        }

        return $responseData;
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
            $merchantRelationRequestTransfer->getStatusOrFail(),
            $this->getFactory()->getConfig()->getEditableMerchantRelationRequestStatuses(),
            true,
        );
    }
}
