<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractStatusChangeController extends AbstractMerchantRelationRequestController
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_TO_SET = 'approved';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_STATUS_CHANGED = 'Merchant relation request has been approved.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CSRF_TOKEN_IS_NOT_VALID = 'CSRF token is not valid.';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function getForm(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): FormInterface;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Symfony\Component\Form\FormInterface $statusChangeMerchantRelationRequestForm
     *
     * @return array<string, mixed>
     */
    abstract protected function getResponseData(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        FormInterface $statusChangeMerchantRelationRequestForm
    ): array;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Symfony\Component\Form\FormInterface $statusChangeMerchantRelationRequestForm
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    abstract protected function getMerchantRelationRequestTransferToUpdate(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        FormInterface $statusChangeMerchantRelationRequestForm
    ): MerchantRelationRequestTransfer;

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

        $statusChangeMerchantRelationRequestForm = $this->getForm($merchantRelationRequestTransfer);
        $statusChangeMerchantRelationRequestForm->handleRequest($request);

        if (!$statusChangeMerchantRelationRequestForm->isSubmitted()) {
            return $this->viewResponse($this->getResponseData($merchantRelationRequestTransfer, $statusChangeMerchantRelationRequestForm));
        }

        if (!$statusChangeMerchantRelationRequestForm->isValid()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_CSRF_TOKEN_IS_NOT_VALID);

            return $this->redirectResponse($this->getEditPageUrl($merchantRelationRequestTransfer));
        }

        $merchantRelationRequestTransfer->setStatus(static::STATUS_TO_SET);
        $merchantRelationRequestCollectionResponseTransfer = $this->updateMerchantRelationRequest(
            $this->getMerchantRelationRequestTransferToUpdate($merchantRelationRequestTransfer, $statusChangeMerchantRelationRequestForm),
        );

        if ($merchantRelationRequestCollectionResponseTransfer->getErrors()->count()) {
            return $this->viewResponse($this->getResponseData($merchantRelationRequestTransfer, $statusChangeMerchantRelationRequestForm));
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_STATUS_CHANGED);

        return $this->redirectResponse($this->getEditPageUrl($merchantRelationRequestTransfer));
    }
}
