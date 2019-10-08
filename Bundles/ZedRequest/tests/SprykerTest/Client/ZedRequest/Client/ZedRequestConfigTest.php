<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ZedRequest\Client;

use Codeception\Test\Unit;
use Spryker\Client\ZedRequest\ZedRequestConfig;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ZedRequest
 * @group Client
 * @group ZedRequestConfigTest
 * Add your own group annotations below this line
 */
class ZedRequestConfigTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ZedRequest\ZedRequestClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOverrideClientConfig()
    {
        $configuredClientOptions = [
            'timeout' => 30,
            'connect_timeout' => 1,
        ];
        $this->tester->setConfig(ZedRequestConstants::CLIENT_OPTIONS, $configuredClientOptions);

        $zedRequestConfig = new ZedRequestConfig();
        $clientOptions = $zedRequestConfig->getClientConfiguration();
        $this->assertSame(30, $clientOptions['timeout']);
        $this->assertSame(1, $clientOptions['connect_timeout']);
    }
}
