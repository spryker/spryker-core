<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Authentication;

use Codeception\Actor;
use Generated\Shared\DataBuilder\GlueAuthenticationRequestBuilder;
use Generated\Shared\DataBuilder\OauthRequestBuilder;
use Generated\Shared\Transfer\ApiTokenAttributesTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

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
class AuthenticationBusinessTester extends Actor
{
    use _generated\AuthenticationBusinessTesterActions;

    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @var string
     */
    protected const TEST_GRANT_TYPE = 'password';

    /**
     * @var string
     */
    protected const TEST_USERNAME = 'harald@spryker.com';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @return \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer
     */
    public function haveGlueAuthenticationRequestTransfer(): GlueAuthenticationRequestTransfer
    {
        $glueAuthenticationRequestContextTransfer = (new GlueAuthenticationRequestContextTransfer())
            ->setRequestApplication(static::GLUE_BACKEND_API_APPLICATION);

        return (new GlueAuthenticationRequestBuilder(
            [
                GlueAuthenticationRequestTransfer::OAUTH_REQUEST => $this->haveOauthRequestTransfer(),
                GlueAuthenticationRequestTransfer::REQUEST_CONTEXT => $glueAuthenticationRequestContextTransfer,
            ],
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    protected function haveOauthRequestTransfer(): OauthRequestTransfer
    {
        return (new OauthRequestBuilder(
            [
                ApiTokenAttributesTransfer::GRANT_TYPE => static::TEST_GRANT_TYPE,
                ApiTokenAttributesTransfer::USERNAME => static::TEST_USERNAME,
                ApiTokenAttributesTransfer::PASSWORD => static::TEST_PASSWORD,
            ],
        ))->build();
    }
}
