<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Controller;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Communication\MerchantRegistrationRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    public function createMerchantRegistrationRequestAction(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        return $this->getFacade()->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }
}
