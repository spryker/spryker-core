<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ServicePointTransfer;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class ServicePointStorageCommunicationTester extends Actor
{
    use _generated\ServicePointStorageCommunicationTesterActions;

    /**
     * @param array<string, mixed> $servicePointSeedData
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointTransferWithStoreRelations(array $servicePointSeedData = []): ServicePointTransfer
    {
        $servicePointTransfer = (new ServicePointBuilder($servicePointSeedData))
            ->build();

        return $this->haveServicePoint($servicePointTransfer->toArray());
    }
}
