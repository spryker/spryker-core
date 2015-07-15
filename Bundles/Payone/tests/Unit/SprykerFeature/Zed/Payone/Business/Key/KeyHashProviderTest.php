<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Payone\Business\Key;

use SprykerFeature\Zed\Payone\Business\Key\HashProvider;

/**
 * @group KeyHash
 */
class KeyHashProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testKeyHash()
    {
        $key = 'abcd1efgh2ijklm3nopq4';
        $expectedHashedKey = hash('md5', $key);

        $keyHashProvider = new HashProvider();
        $systemHashedKey = $keyHashProvider->hash($key);

        $this->assertEquals($expectedHashedKey, $systemHashedKey);
    }

}
