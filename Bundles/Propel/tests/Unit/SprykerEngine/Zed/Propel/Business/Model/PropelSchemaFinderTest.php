<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;

/**
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelSchemaFinder
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
