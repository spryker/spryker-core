<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthCodeFlow;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

/**
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
class OauthCodeFlowCommunicationTester extends Actor
{
    use _generated\OauthCodeFlowCommunicationTesterActions;

    /**
     * @uses \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig::GRANT_TYPE_AUTHORIZATION_CODE
     *
     * @var string
     */
    public const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';

    /**
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    public function createOauthRequestTransfer(): OauthRequestTransfer
    {
        return new OauthRequestTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer
     */
    public function createGlueAuthenticationRequestContextTransfer(): GlueAuthenticationRequestContextTransfer
    {
        return new GlueAuthenticationRequestContextTransfer();
    }
}
