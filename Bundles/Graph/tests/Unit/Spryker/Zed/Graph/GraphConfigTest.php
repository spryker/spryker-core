<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Graph;

use Spryker\Zed\Graph\GraphConfig;

/**
 * @group Spryker
 * @group Zed
 * @group Graph
 */
class GraphConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetGraphAdapterName()
    {
        $config = new GraphConfig();
        $this->assertInternalType('string', $config->getGraphAdapterName());
    }

}
