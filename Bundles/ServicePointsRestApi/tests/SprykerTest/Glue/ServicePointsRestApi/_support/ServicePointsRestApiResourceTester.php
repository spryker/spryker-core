<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsRestApi;

use Codeception\Actor;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiResourceInterface;

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
 * @SuppressWarnings(\SprykerTest\Glue\ServicePointsRestApi\PHPMD)
 */
class ServicePointsRestApiResourceTester extends Actor
{
    use _generated\ServicePointsRestApiResourceTesterActions;

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiResourceInterface
     */
    public function getResource(): ServicePointsRestApiResourceInterface
    {
        return $this->getLocator()->servicePointsRestApi()->resource();
    }
}
