<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest;

use Codeception\Actor;
use Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceBridge;
use Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface;
use Spryker\Shared\ZedRequest\Logger\ZedRequestInMemoryLogger;
use Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ZedRequestSharedTester extends Actor
{
    use _generated\ZedRequestSharedTesterActions;

    /**
     * @return \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface
     */
    public function createZedRequestInMemoryLogger(): ZedRequestLoggerInterface
    {
        return new ZedRequestInMemoryLogger(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ZedRequestToUtilEncodingServiceInterface
    {
        return new ZedRequestToUtilEncodingServiceBridge(
            $this->getLocator()->utilEncoding()->service()
        );
    }
}
