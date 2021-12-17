<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\ApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group ApiApplication
 * @group GlueStorefrontApiApplicationTest
 * Add your own group annotations below this line
 */
class GlueStorefrontApiApplicationTest extends Unit
{
    /**
     * @return void
     */
    public function testRunWillCreateApplication(): void
    {
        $glueStorefrontApiApplication = new GlueStorefrontApiApplication(null, []);
        $glueStorefrontApiApplication->run();

        $this->expectOutputString('"Welcome to the future Storefront API"');
    }
}
