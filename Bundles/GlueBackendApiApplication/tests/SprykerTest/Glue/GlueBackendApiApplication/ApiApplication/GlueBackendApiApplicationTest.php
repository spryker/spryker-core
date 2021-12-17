<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\ApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group ApiApplication
 * @group GlueBackendApiApplicationTest
 * Add your own group annotations below this line
 */
class GlueBackendApiApplicationTest extends Unit
{
    /**
     * @return void
     */
    public function testRunWillCreateApplication(): void
    {
        $glueBackendApiApplication = new GlueBackendApiApplication(null, []);
        $glueBackendApiApplication->run();

        $this->expectOutputString('"Welcome to the future Backend API"');
    }
}
