<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Payone\Business\Key;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Payone\Business\Key\HashProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Payone
 * @group Business
 * @group Key
 * @group KeyHashProviderTest
 */
class KeyHashProviderTest extends PHPUnit_Framework_TestCase
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
