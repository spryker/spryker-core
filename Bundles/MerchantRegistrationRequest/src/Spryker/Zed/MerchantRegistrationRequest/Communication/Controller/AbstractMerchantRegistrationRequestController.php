<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Controller;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRegistrationRequest\Communication\Table\MerchantRegistrationRequestTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Communication\MerchantRegistrationRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 */
abstract class AbstractMerchantRegistrationRequestController extends AbstractController
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_REGISTRATION_REQUEST_NOT_FOUND = 'Merchant registration request not found.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CSRF_TOKEN_IS_NOT_VALID = 'CSRF token is not valid.';

    /**
     * @uses \Spryker\Zed\MerchantRegistrationRequest\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_REGISTRATION_REQUEST_LIST = '/merchant-registration-request/list';

    /**
     * @uses \Spryker\Zed\MerchantRegistrationRequest\Communication\Controller\ViewController::indexAction()
     *
     * @var string
     */
    protected const URL_VIEW_MERCHANT_REGISTRATION_REQUEST = '/merchant-registration-request/view';

    protected function findMerchantRegistrationRequest(Request $request): ?MerchantRegistrationRequestTransfer
    {
        $idMerchantRegistrationRequest = $this->castId(
            $request->get(MerchantRegistrationRequestTable::PARAM_ID_MERCHANT_REGISTRATION_REQUEST),
        );

        return $this->getFacade()->findMerchantRegistrationRequestById($idMerchantRegistrationRequest);
    }

    protected function getViewPageUrl(MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer): string
    {
        return sprintf(
            '%s?%s=%d',
            static::URL_VIEW_MERCHANT_REGISTRATION_REQUEST,
            MerchantRegistrationRequestTable::PARAM_ID_MERCHANT_REGISTRATION_REQUEST,
            $merchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest(),
        );
    }

    protected function isMerchantRegistrationRequestAcceptable(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): bool {
        return in_array($merchantRegistrationRequestTransfer->getStatus(), $this->getFactory()->getConfig()->getAcceptableStatuses());
    }

    protected function isMerchantRegistrationRequestRejectable(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): bool {
        return in_array($merchantRegistrationRequestTransfer->getStatus(), $this->getFactory()->getConfig()->getRejectableStatuses());
    }

    /**
     * @param list<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return void
     */
    protected function addErrorMessages(array $errorTransfers): void
    {
        foreach ($errorTransfers as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail());
        }
    }
}
