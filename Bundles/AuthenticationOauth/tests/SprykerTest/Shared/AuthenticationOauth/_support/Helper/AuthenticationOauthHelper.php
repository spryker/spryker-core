<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AuthenticationOauth\Helper;

use Codeception\Module;
use Codeception\Stub;
use Generated\Shared\DataBuilder\GlueAuthenticationRequestBuilder;
use Generated\Shared\Transfer\ApiTokenAttributesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Currency\CurrencyDependencyProvider;
use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AuthenticationOauthHelper extends Module
{
    use LocatorHelperTrait;
    use DependencyHelperTrait;

    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * @var string
     */
    protected const GRANT_TYPE_PASSWORD = 'password';

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function havePasswordAuthorizationToBackendApi(UserTransfer $userTransfer): OauthResponseTransfer
    {
        $glueAuthenticationRequestTransfer = $this->createBackendApiGluePasswordAuthenticationRequestTransfer($userTransfer);

        $glueAuthenticationResponseTransfer = $this->getLocator()
            ->authentication()
            ->facade()
            ->authenticate($glueAuthenticationRequestTransfer);

        $this->assertTrue($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid(), 'OAuth token request failed');

        return $glueAuthenticationResponseTransfer->getOauthResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function havePasswordAuthorizationToStorefrontApi(CustomerTransfer $customerTransfer): OauthResponseTransfer
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface $currencyToSessionMock */
        $currencyToSessionMock = Stub::makeEmpty(CurrencyToSessionInterface::class);
        $this->getDependencyHelper()->setDependency(CurrencyDependencyProvider::CLIENT_SESSION, $currencyToSessionMock);

        $glueAuthenticationRequestTransfer = $this->createStorefrontApiGluePasswordAuthenticationRequestTransfer($customerTransfer);

        $glueAuthenticationResponseTransfer = $this->getLocator()
            ->authentication()
            ->client()
            ->authenticate($glueAuthenticationRequestTransfer);

        $this->assertTrue($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid(), 'OAuth token request failed');

        return $glueAuthenticationResponseTransfer->getOauthResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer
     */
    protected function createBackendApiGluePasswordAuthenticationRequestTransfer(UserTransfer $userTransfer): GlueAuthenticationRequestTransfer
    {
        $glueAuthenticationRequestContextTransfer = (new GlueAuthenticationRequestContextTransfer())
            ->setRequestApplication(static::GLUE_BACKEND_API_APPLICATION);

        return (new GlueAuthenticationRequestBuilder([
            GlueAuthenticationRequestTransfer::REQUEST_CONTEXT => $glueAuthenticationRequestContextTransfer,
        ]))->withOauthRequest([
            ApiTokenAttributesTransfer::GRANT_TYPE => static::GRANT_TYPE_PASSWORD,
            ApiTokenAttributesTransfer::USERNAME => $userTransfer->getUsernameOrFail(),
            ApiTokenAttributesTransfer::PASSWORD => $userTransfer->getPasswordOrFail(),
        ])->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer
     */
    protected function createStorefrontApiGluePasswordAuthenticationRequestTransfer(CustomerTransfer $customerTransfer): GlueAuthenticationRequestTransfer
    {
        $glueAuthenticationRequestContextTransfer = (new GlueAuthenticationRequestContextTransfer())
            ->setRequestApplication(static::GLUE_STOREFRONT_API_APPLICATION);

        return (new GlueAuthenticationRequestBuilder([
            GlueAuthenticationRequestTransfer::REQUEST_CONTEXT => $glueAuthenticationRequestContextTransfer,
        ]))->withOauthRequest([
            ApiTokenAttributesTransfer::GRANT_TYPE => static::GRANT_TYPE_PASSWORD,
            ApiTokenAttributesTransfer::USERNAME => $customerTransfer->getUsernameOrFail(),
            ApiTokenAttributesTransfer::PASSWORD => $customerTransfer->getPasswordOrFail(),
        ])->build();
    }
}
