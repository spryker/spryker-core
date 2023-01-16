<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheck;

use Codeception\Actor;
use Generated\Shared\DataBuilder\PublishAndSynchronizeHealthCheckBuilder;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheck;

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
class PublishAndSynchronizeHealthCheckBusinessTester extends Actor
{
    use _generated\PublishAndSynchronizeHealthCheckBusinessTesterActions;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function createPublishAndSynchronizeHealthCheck(array $override = []): PublishAndSynchronizeHealthCheckTransfer
    {
        $publishAndSynchronizeHealthCheckTransfer = (new PublishAndSynchronizeHealthCheckBuilder($override))->build();

        $publishAndSynchronizeHealthCheckEntity = (new SpyPublishAndSynchronizeHealthCheck())
            ->fromArray($publishAndSynchronizeHealthCheckTransfer->toArray());

        $publishAndSynchronizeHealthCheckEntity->save();

        return $publishAndSynchronizeHealthCheckTransfer->fromArray($publishAndSynchronizeHealthCheckEntity->toArray(), true);
    }
}
