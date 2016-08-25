<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelSchemaFinderTest
 */
class PropelSchemaFinderTest extends AbstractPropelSchemaTest
{

    /**
     * @return void
     */
    public function testGetSchemasShouldReturnIterateableFileCollection()
    {
        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory()]
        );

        $this->assertInstanceOf('Symfony\Component\Finder\Finder', $schemaFinder->getSchemaFiles());
    }

}
