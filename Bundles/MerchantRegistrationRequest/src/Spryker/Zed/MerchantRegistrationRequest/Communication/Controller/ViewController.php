<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Communication\MerchantRegistrationRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 */
class ViewController extends AbstractMerchantRegistrationRequestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        $merchantRegistrationRequestTransfer = $this->findMerchantRegistrationRequest($request);

        if ($merchantRegistrationRequestTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_MERCHANT_REGISTRATION_REQUEST_NOT_FOUND);

            return $this->redirectResponse(static::URL_MERCHANT_REGISTRATION_REQUEST_LIST);
        }

        $merchantRegistrationRequestTransfer = $this->getFacade()
            ->expandMerchantRegistrationRequestWithCommentThread($merchantRegistrationRequestTransfer);

        return $this->viewResponse([
            'merchantRegistrationRequest' => $merchantRegistrationRequestTransfer,
            'urlMerchantRegistrationRequestList' => static::URL_MERCHANT_REGISTRATION_REQUEST_LIST,
            'statusClassLabelMapping' => $this->getFactory()->getConfig()->getStatusClassLabelMapping(),
            'isMerchantRegistrationRequestAcceptable' => $this->isMerchantRegistrationRequestAcceptable($merchantRegistrationRequestTransfer),
            'isMerchantRegistrationRequestRejectable' => $this->isMerchantRegistrationRequestRejectable($merchantRegistrationRequestTransfer),
            'commentThreadOwnerType' => $this->getFactory()->getConfig()->getCommentThreadOwnerType(),
        ]);
    }
}
