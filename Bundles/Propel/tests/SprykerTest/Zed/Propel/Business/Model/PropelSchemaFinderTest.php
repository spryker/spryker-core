<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelSchemaFinderTest
 * Add your own group annotations below this line
 */
class PropelSchemaFinderTest extends AbstractPropelSchemaTest
{
    /**
     * @return void
     */
    public function testGetSchemasShouldReturnIterateableFileCollection(): void
    {
        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory()],
        );

        $this->assertInstanceOf(Finder::class, $schemaFinder->getSchemaFiles());
    }
}
