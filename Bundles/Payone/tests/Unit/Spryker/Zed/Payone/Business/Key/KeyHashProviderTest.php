<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Payone\Business\Key;

use Spryker\Zed\Payone\Business\Key\HashProvider;

/**
 * @group KeyHash
 */
class KeyHashProviderTest extends \PHPUnit_Framework_TestCase
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
