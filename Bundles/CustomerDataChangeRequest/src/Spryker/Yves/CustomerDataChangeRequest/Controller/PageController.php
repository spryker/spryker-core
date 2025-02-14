<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest\Controller;

use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\CustomerDataChangeRequest\CustomerDataChangeRequestFactory getFactory()
 * @method \Spryker\Client\CustomerDataChangeRequest\CustomerDataChangeRequestClientInterface getClient()
 * @method \Spryker\Yves\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 */
class PageController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_EMAIL_CHANGE_REQUEST_SUCCESS = 'customer.data_change_request.email_change.success';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_EMAIL_CHANGE_REQUEST_ERROR = 'customer.data_change_request.email_change.error';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_PROFILE
     *
     * @var string
     */
    protected const ROUTE_NAME_CUSTOMER_PROFILE = 'customer/profile';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeEmailAction(Request $request): RedirectResponse
    {
        $customerDataChangeRequestTransfer = new CustomerDataChangeRequestTransfer();
        $customerDataChangeRequestTransfer->setVerificationToken((string)$request->query->get('verification_token'));
        $customerDataChangeRequestTransfer->setType(CustomerDataChangeRequestTypeEnum::EMAIL->value);

        $customerTransfer = $this->getFactory()->getCustomerClient()->findCustomerRawData();

        if (!$customerTransfer) {
            $this->addErrorMessage(static::GLOSSARY_KEY_EMAIL_CHANGE_REQUEST_ERROR);

            return $this->redirectResponseInternal(static::ROUTE_NAME_CUSTOMER_PROFILE);
        }

        $customerDataChangeResponseTransfer = $this->getClient()->changeCustomerData($customerDataChangeRequestTransfer);

        if ($customerDataChangeResponseTransfer->getErrors()->offsetExists(0)) {
            foreach ($customerDataChangeResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }

            return $this->redirectResponseInternal(static::ROUTE_NAME_CUSTOMER_PROFILE);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_EMAIL_CHANGE_REQUEST_SUCCESS);

        return $this->redirectResponseInternal(static::ROUTE_NAME_CUSTOMER_PROFILE);
    }
}
