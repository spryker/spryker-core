<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Storage\Provider;

use PHPUnit_Framework_TestCase;
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
class AbstractKvProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetConfigByKvNameShouldReturnArray()
    {
        $abstractKvProviderMock = $this->getAbstractKvProviderMock();
        $config = $abstractKvProviderMock->getConfigByKvName(AbstractKvProvider::KV_ADAPTER_REDIS);

        $this->assertInternalType('array', $config);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Storage\Provider\AbstractKvProvider
     */
    protected function getAbstractKvProviderMock()
    {
        return $this->getMockBuilder(AbstractKvProvider::class)->getMockForAbstractClass();
    }

}
