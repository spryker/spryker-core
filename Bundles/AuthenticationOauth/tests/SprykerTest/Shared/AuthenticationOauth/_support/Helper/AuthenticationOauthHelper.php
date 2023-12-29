<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AuthenticationOauth\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\GlueAuthenticationRequestBuilder;
use Generated\Shared\DataBuilder\OauthRequestBuilder;
use Generated\Shared\Transfer\ApiTokenAttributesTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AuthenticationOauthHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

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
}
