<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OauthRequestBuilder;
use Generated\Shared\DataBuilder\OauthResponseBuilder;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

/**
 * Inherited Methods
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
 * @method \Spryker\Zed\AuthRestApi\Business\AuthRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class AuthRestApiBusinessTester extends Actor
{
    use _generated\AuthRestApiBusinessTesterActions;

    public const TEST_CUSTOMER_REFERENCE = 'DE--666';

    public const TEST_ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:DE--666';

    /**
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    public function prepareOauthRequestTransfer(): OauthRequestTransfer
    {
        return (new OauthRequestBuilder(
            [
                'customerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function prepareOauthResponseTransfer(): OauthResponseTransfer
    {
        return (new OauthResponseBuilder(
            [
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'isValid' => true,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function prepareInvalidOauthResponseTransfer(): OauthResponseTransfer
    {
        return (new OauthResponseBuilder(
            [
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'isValid' => false,
            ]
        ))->build();
    }
}
