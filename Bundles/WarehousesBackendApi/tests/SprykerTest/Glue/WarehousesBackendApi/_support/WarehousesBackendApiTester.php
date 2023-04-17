<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\WarehousesBackendApi;

use Codeception\Actor;
use Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiResourceInterface;

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
class WarehousesBackendApiTester extends Actor
{
    use _generated\WarehousesBackendApiTesterActions;

    /**
     * @return \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiResourceInterface
     */
    public function getResource(): WarehousesBackendApiResourceInterface
    {
        return $this->getLocator()->warehousesBackendApi()->resource();
    }
}
