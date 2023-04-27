<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Service\Oauth;

use Codeception\Actor;
use Spryker\Service\Oauth\OauthServiceInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class OauthServiceTester extends Actor
{
    use _generated\OauthServiceTesterActions;

    /**
     * @return \Spryker\Service\Oauth\OauthServiceInterface
     */
    public function getOauthService(): OauthServiceInterface
    {
        return $this->getLocator()->oauth()->service();
    }
}
