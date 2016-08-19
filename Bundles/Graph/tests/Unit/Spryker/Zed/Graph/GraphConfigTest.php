<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Graph;

use Spryker\Zed\Graph\GraphConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Graph
 * @group GraphConfigTest
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
