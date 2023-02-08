<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation\Mapper;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;

interface OauthCustomerValidationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function mapOauthAccessTokenDataTransferToCustomerIdentifierTransfer(
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer,
        CustomerIdentifierTransfer $customerIdentifierTransfer
    ): CustomerIdentifierTransfer;
}
