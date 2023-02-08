<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\OauthCustomerValidation;

use Codeception\Actor;
use Codeception\Stub;
use DateTime;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface;
use Spryker\Client\OauthCustomerValidation\OauthCustomerValidationClientInterface;
use Spryker\Client\OauthCustomerValidation\OauthCustomerValidationDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class OauthCustomerValidationClientTester extends Actor
{
    use _generated\OauthCustomerValidationClientTesterActions;

    /**
     * @uses \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig::SCOPE_CUSTOMER
     *
     * @var string
     */
    protected const SCOPE_CUSTOMER = 'customer';

    /**
     * @uses \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::SCOPE_USER
     *
     * @var string
     */
    protected const SCOPE_USER = 'user';

    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE = 'TEST--1';

    /**
     * @return \Spryker\Client\OauthCustomerValidation\OauthCustomerValidationClientInterface
     */
    public function getOauthCustomerValidationClient(): OauthCustomerValidationClientInterface
    {
        return $this->getLocator()
            ->oauthCustomerValidation()
            ->client();
    }

    /**
     * @param bool $isCustomer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    public function getOauthAccessTokenDataTransfer(bool $isCustomer): OauthAccessTokenDataTransfer
    {
        $customerIdentifierTransfer = (new CustomerIdentifierTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE);

        return (new OauthAccessTokenDataTransfer())
            ->setOauthIssuedAt(
                (new DateTime('-1 minute'))->getTimestamp(),
            )
            ->setOauthUserId(
                json_encode($customerIdentifierTransfer->toArray()),
            )
            ->addOauthScopes(
                $isCustomer === true ? static::SCOPE_CUSTOMER : static::SCOPE_USER,
            );
    }

    /**
     * @param \DateTime|null $anonymizedAt
     * @param \DateTime|null $passwordUpdatedAt
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollectionTransfer(
        ?DateTime $anonymizedAt,
        ?DateTime $passwordUpdatedAt
    ): InvalidatedCustomerCollectionTransfer {
        $invalidatedCustomerTransfer = (new InvalidatedCustomerTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE);

        if ($anonymizedAt !== null) {
            $invalidatedCustomerTransfer->setAnonymizedAt(
                $anonymizedAt->format('Y-m-d H:i:s'),
            );
        }

        if ($passwordUpdatedAt !== null) {
            $invalidatedCustomerTransfer->setPasswordUpdatedAt(
                $passwordUpdatedAt->format('Y-m-d H:i:s'),
            );
        }

        return (new InvalidatedCustomerCollectionTransfer())
            ->addInvalidatedCustomer($invalidatedCustomerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return void
     */
    public function setOauthServiceMock(OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer): void
    {
        $oauthServiceMock = Stub::makeEmpty(
            OauthCustomerValidationToOauthServiceInterface::class,
            [
                'extractAccessTokenData' => function () use ($oauthAccessTokenDataTransfer) {
                    return $oauthAccessTokenDataTransfer;
                },
            ],
        );
        $oauthServiceMock->expects(new InvokedCount(1))->method('extractAccessTokenData');

        $this->setDependency(OauthCustomerValidationDependencyProvider::SERVICE_OAUTH, $oauthServiceMock);
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
     *
     * @return void
     */
    public function setCustomerStorageClientMock(
        InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
    ): void {
        $customerStorageClientMock = Stub::makeEmpty(
            OauthCustomerValidationToCustomerStorageClientInterface::class,
            [
                'getInvalidatedCustomerCollection' => function () use ($invalidatedCustomerCollectionTransfer) {
                    return $invalidatedCustomerCollectionTransfer;
                },
            ],
        );
        $customerStorageClientMock->expects(new InvokedCount(1))->method('getInvalidatedCustomerCollection');

        $this->setDependency(OauthCustomerValidationDependencyProvider::CLIENT_CUSTOMER_STORAGE, $customerStorageClientMock);
    }
}
