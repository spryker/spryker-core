<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payone\Business\Key;

use Codeception\Test\Unit;
use Spryker\Zed\Payone\Business\Key\HashProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payone
 * @group Business
 * @group Key
 * @group KeyHashProviderTest
 * Add your own group annotations below this line
 */
class KeyHashProviderTest extends Unit
{

    /**
     * @return void
     */
    public function testKeyHash()
    {
        $key = 'abcd1efgh2ijklm3nopq4';
        $expectedHashedKey = hash('md5', $key);

        $keyHashProvider = new HashProvider();
        $systemHashedKey = $keyHashProvider->hash($key);

        $this->assertEquals($expectedHashedKey, $systemHashedKey);
    }

}
