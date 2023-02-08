<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation\Validator;

use DateTime;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerConditionsTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface;
use Spryker\Client\OauthCustomerValidation\Mapper\OauthCustomerValidationMapperInterface;

class InvalidatedCustomerAccessTokenValidator implements InvalidatedCustomerAccessTokenValidatorInterface
{
    /**
     * @uses \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig::SCOPE_CUSTOMER
     *
     * @var string
     */
    protected const SCOPE_CUSTOMER = 'customer';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALIDATED_CUSTOMER = 'Customer validation failed.';

    /**
     * @var string
     */
    protected const ERROR_TYPE_INVALIDATED_CUSTOMER = 'invalidated_customer';

    /**
     * @var \Spryker\Client\OauthCustomerValidation\Mapper\OauthCustomerValidationMapperInterface
     */
    protected OauthCustomerValidationMapperInterface $oauthCustomerValidationMapper;

    /**
     * @var \Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface
     */
    protected OauthCustomerValidationToCustomerStorageClientInterface $customerStorageClient;

    /**
     * @var \Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface
     */
    protected OauthCustomerValidationToOauthServiceInterface $oauthService;

    /**
     * @param \Spryker\Client\OauthCustomerValidation\Mapper\OauthCustomerValidationMapperInterface $oauthCustomerValidationMapper
     * @param \Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface $customerStorageClient
     * @param \Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface $oauthService
     */
    public function __construct(
        OauthCustomerValidationMapperInterface $oauthCustomerValidationMapper,
        OauthCustomerValidationToCustomerStorageClientInterface $customerStorageClient,
        OauthCustomerValidationToOauthServiceInterface $oauthService
    ) {
        $this->oauthCustomerValidationMapper = $oauthCustomerValidationMapper;
        $this->customerStorageClient = $customerStorageClient;
        $this->oauthService = $oauthService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateInvalidatedCustomerAccessToken(
        OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer,
        OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        $accessToken = $oauthAccessTokenValidationRequestTransfer->getAccessToken();
        if (!$accessToken) {
            return $oauthAccessTokenValidationResponseTransfer;
        }

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($accessToken);
        if (!in_array(static::SCOPE_CUSTOMER, $oauthAccessTokenDataTransfer->getOauthScopes(), true)) {
            return $oauthAccessTokenValidationResponseTransfer;
        }

        $customerReference = $this->oauthCustomerValidationMapper
            ->mapOauthAccessTokenDataTransferToCustomerIdentifierTransfer(
                $oauthAccessTokenDataTransfer,
                new CustomerIdentifierTransfer(),
            )->getCustomerReference();

        if (!$customerReference) {
            return $oauthAccessTokenValidationResponseTransfer;
        }

        $invalidatedCustomerCollectionTransfer = $this->customerStorageClient->getInvalidatedCustomerCollection(
            $this->createInvalidatedCustomerCriteriaTransfer($customerReference),
        );

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\InvalidatedCustomerTransfer> $invalidatedCustomerCollection */
        $invalidatedCustomerCollection = $invalidatedCustomerCollectionTransfer->getInvalidatedCustomers();
        if ($invalidatedCustomerCollection->count() === 0) {
            return $oauthAccessTokenValidationResponseTransfer;
        }

        $invalidatedCustomerTransfer = $invalidatedCustomerCollection->getIterator()->current();

        if ($invalidatedCustomerTransfer->getAnonymizedAt() !== null) {
            $oauthAccessTokenValidationResponseTransfer = $this->setError(
                $oauthAccessTokenValidationResponseTransfer,
            );
        }

        if (
            $invalidatedCustomerTransfer->getPasswordUpdatedAt() !== null &&
            $this->isPasswordUpdated($invalidatedCustomerTransfer, $oauthAccessTokenDataTransfer) === true
        ) {
            $oauthAccessTokenValidationResponseTransfer = $this->setError(
                $oauthAccessTokenValidationResponseTransfer,
            );
        }

        return $oauthAccessTokenValidationResponseTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer
     */
    protected function createInvalidatedCustomerCriteriaTransfer(
        string $customerReference
    ): InvalidatedCustomerCriteriaTransfer {
        $invalidatedCustomerConditionsTransfer = (new InvalidatedCustomerConditionsTransfer())
            ->addCustomerReference($customerReference);

        return (new InvalidatedCustomerCriteriaTransfer())
            ->setInvalidatedCustomerConditions($invalidatedCustomerConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    protected function setError(
        OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        $oauthErrorTransfer = (new OauthErrorTransfer())
            ->setErrorType(static::ERROR_TYPE_INVALIDATED_CUSTOMER)
            ->setMessage(static::ERROR_MESSAGE_INVALIDATED_CUSTOMER);

        $oauthAccessTokenValidationResponseTransfer->setError($oauthErrorTransfer);

        return $oauthAccessTokenValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerTransfer $invalidatedCustomerTransfer
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return bool
     */
    protected function isPasswordUpdated(
        InvalidatedCustomerTransfer $invalidatedCustomerTransfer,
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
    ): bool {
        $oauthIssuedAt = $oauthAccessTokenDataTransfer->getOauthIssuedAt();
        $passwordUpdatedAt = $invalidatedCustomerTransfer->getPasswordUpdatedAt();
        if (!$oauthIssuedAt || !$passwordUpdatedAt) {
            return false;
        }

        $sessionCreatedAt = (new DateTime())->setTimestamp($oauthIssuedAt);
        $passwordUpdatedAt = new DateTime($passwordUpdatedAt);

        return $sessionCreatedAt <= $passwordUpdatedAt;
    }
}
