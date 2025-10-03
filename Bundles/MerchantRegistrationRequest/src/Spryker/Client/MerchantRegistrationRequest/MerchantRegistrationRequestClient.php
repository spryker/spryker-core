<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRegistrationRequest;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantRegistrationRequest\MerchantRegistrationRequestFactory getFactory()
 */
class MerchantRegistrationRequestClient extends AbstractClient implements MerchantRegistrationRequestClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        return $this->getFactory()
            ->createZedMerchantRegistrationRequestStub()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }
}
