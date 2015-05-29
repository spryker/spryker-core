<?php

namespace Unit\SprykerFeature\Zed\Setup\Business\Model\Propel;

use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaFinder;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
 * @group Business
 * @group PropelSchemaFinder
 */
class PropelSchemaFinderTest extends AbstractPropelSchemaTest
{

    public function testGetSchemasShouldReturnIterateableFileCollection()
    {
        $schemaFinder = new PropelSchemaFinder(
            [$this->getFixtureDirectory()],
            'file name pattern'
        );

        $this->assertInstanceOf('Symfony\Component\Finder\Finder', $schemaFinder->getSchemaFiles());
    }
}
