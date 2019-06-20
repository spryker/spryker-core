<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Storage\Provider;

use Codeception\Test\Unit;
use Spryker\Shared\Storage\Provider\AbstractKvProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Storage
 * @group Provider
 * @group AbstractKvProviderTest
 * Add your own group annotations below this line
 */
class AbstractKvProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testGetConfigByKvNameShouldReturnArray()
    {
        $abstractKvProviderMock = $this->getAbstractKvProviderMock();
        $config = $abstractKvProviderMock->getConfigByKvName(AbstractKvProvider::KV_ADAPTER_REDIS);

        $this->assertIsArray($config);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Storage\Provider\AbstractKvProvider
     */
    protected function getAbstractKvProviderMock()
    {
        $abstractKvProviderMock = $this->getMockBuilder(AbstractKvProvider::class)
            ->setMethods(['getConnectionParameters'])
            ->getMockForAbstractClass();
        $abstractKvProviderMock->method('getConnectionParameters')
            ->willReturn([]);

        return $abstractKvProviderMock;
    }
}
