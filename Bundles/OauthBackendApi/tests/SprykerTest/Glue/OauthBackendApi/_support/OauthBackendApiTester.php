<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthBackendApi;

use Codeception\Actor;
use Spryker\Glue\OauthBackendApi\Plugin\GlueApplication\BackendApiAccessTokenValidatorPlugin;

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
class OauthBackendApiTester extends Actor
{
    use _generated\OauthBackendApiTesterActions;

    /**
     * @var string
     */
    public const AUTHORIZATION = 'authorization';

    /**
     * @return \Spryker\Glue\OauthBackendApi\Plugin\GlueApplication\BackendApiAccessTokenValidatorPlugin
     */
    public function createBackendApiAccessTokenValidatorPlugin(): BackendApiAccessTokenValidatorPlugin
    {
        return new BackendApiAccessTokenValidatorPlugin();
    }
}
