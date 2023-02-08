<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation\Mapper;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceInterface;

class OauthCustomerValidationMapper implements OauthCustomerValidationMapperInterface
{
    /**
     * @var \Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceInterface
     */
    protected OauthCustomerValidationToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthCustomerValidationToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function mapOauthAccessTokenDataTransferToCustomerIdentifierTransfer(
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer,
        CustomerIdentifierTransfer $customerIdentifierTransfer
    ): CustomerIdentifierTransfer {
        $oauthUserId = $oauthAccessTokenDataTransfer->getOauthUserId();
        if (!$oauthUserId) {
            return $customerIdentifierTransfer;
        }

        $oauthUserIdDecoded = $this->utilEncodingService->decodeJson($oauthUserId, true);
        if ($oauthUserIdDecoded !== null) {
            $customerIdentifierTransfer->fromArray((array)$oauthUserIdDecoded, true);
        }

        return $customerIdentifierTransfer;
    }
}
