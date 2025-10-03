<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Controller;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Communication\MerchantRegistrationRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 */
class RejectMerchantRegistrationRequestController extends AbstractMerchantRegistrationRequestController
{
    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_STATUS_CHANGED = 'Merchant Registration Request from %s was rejected.';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        $merchantRegistrationRequestTransfer = $this->findMerchantRegistrationRequest($request);

        if ($merchantRegistrationRequestTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_MERCHANT_REGISTRATION_REQUEST_NOT_FOUND);

            return $this->redirectResponse(static::URL_MERCHANT_REGISTRATION_REQUEST_LIST);
        }

        $rejectMerchantRegistrationRequestForm = $this->getFactory()
            ->createRejectMerchantRegistrationRequestForm();
        $rejectMerchantRegistrationRequestForm->handleRequest($request);

        if (!$rejectMerchantRegistrationRequestForm->isSubmitted()) {
            return $this->viewResponse($this->getResponseData($merchantRegistrationRequestTransfer, $rejectMerchantRegistrationRequestForm));
        }

        if (!$rejectMerchantRegistrationRequestForm->isValid()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_CSRF_TOKEN_IS_NOT_VALID);

            return $this->redirectResponse($this->getViewPageUrl($merchantRegistrationRequestTransfer));
        }

        $merchantRegistrationResponseTransfer = $this->getFacade()
            ->rejectMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        if (!$merchantRegistrationResponseTransfer->getIsSuccess()) {
            $this->addErrorMessages($merchantRegistrationResponseTransfer->getErrors()->getArrayCopy());

            return $this->redirectResponse($this->getViewPageUrl($merchantRegistrationRequestTransfer));
        }

        $this->addSuccessMessage(sprintf(static::SUCCESS_MESSAGE_STATUS_CHANGED, $merchantRegistrationRequestTransfer->getEmail()));

        return $this->redirectResponse(static::URL_MERCHANT_REGISTRATION_REQUEST_LIST);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        FormInterface $statusChangeMerchantRegistrationRequestForm
    ): array {
        return [
            'rejectMerchantRegistrationRequestForm' => $statusChangeMerchantRegistrationRequestForm->createView(),
            'viewMerchantRegistrationRequestUrl' => $this->getViewPageUrl($merchantRegistrationRequestTransfer),
            'merchantRegistrationRequest' => $merchantRegistrationRequestTransfer,
        ];
    }
}
