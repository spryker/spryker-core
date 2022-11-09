<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ZedRequest;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Shared\ZedRequest\Client\AbstractZedClient;
use Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface;

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
 * @method \Spryker\Client\ZedRequest\ZedRequestFactory getFactory($moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ZedRequestClientTester extends Actor
{
    use _generated\ZedRequestClientTesterActions;

    /**
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    public function createLoggableZedClient(): AbstractZedClientInterface
    {
        return $this->getFactory()->createLoggableZedClient();
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return void
     */
    public function mockCreateZedClient(array $params = []): void
    {
        $zedRequestClientStub = Stub::makeEmpty(AbstractZedClient::class, $params);

        $this->mockFactoryMethod('createZedClient', $zedRequestClientStub);
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $expectedMessages
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $actualMessages
     *
     * @return void
     */
    public function assertMessageAreSame(array $expectedMessages, array $actualMessages): void
    {
        $actualMessagesSize = count($actualMessages);
        $this->assertEquals(count($expectedMessages), $actualMessagesSize);

        for ($i = 0; $i < $actualMessagesSize; ++$i) {
            $this->assertSame($expectedMessages[$i], $actualMessages[$i]);
        }
    }
}
