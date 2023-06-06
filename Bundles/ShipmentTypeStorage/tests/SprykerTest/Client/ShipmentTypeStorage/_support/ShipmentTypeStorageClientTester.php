<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShipmentTypeStorage;

use Codeception\Actor;

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
 * @method \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface getClient(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentTypeStorageClientTester extends Actor
{
    use _generated\ShipmentTypeStorageClientTesterActions;

    /**
     * @param string $key
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function setStorageData(string $key, array $data): void
    {
        $this->mockStorageData($key, json_encode($data));
    }
}
