<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchema;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMerger;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaWriter;
use Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceBridge;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelSchemaTest
 * Add your own group annotations below this line
 */
class PropelSchemaTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixtureTargetDirectory());
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'PropelSchema';
    }

    /**
     * @return string
     */
    private function getFixtureTargetDirectory()
    {
        return $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Target';
    }

    /**
     * @return void
     */
    public function testCopyShouldCopyFileFromSourceToTargetDirectoryWithoutMergingIfOnlyOneFileBySchemaNameExist()
    {
        $finder = new PropelSchemaFinder([$this->getFixtureDirectory()]);
        $groupedFinder = new PropelGroupedSchemaFinder($finder);
        $writer = new PropelSchemaWriter(new Filesystem(), $this->getFixtureTargetDirectory());
        $merger = $this->createPropelSchemaMerger();

        $this->assertFalse(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_foo.schema.xml'));
        $schema = new PropelSchema($groupedFinder, $writer, $merger);
        $schema->copy();

        $this->assertTrue(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_foo.schema.xml'));
    }

    /**
     * @return void
     */
    public function testCopyShouldMergeAndCopyFileFromSourceToTargetDirectoryIfMoreThenOneFileBySchemaNameExist()
    {
        $finder = new PropelSchemaFinder([
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Project',
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Vendor',
        ]);
        $groupedFinder = new PropelGroupedSchemaFinder($finder);
        $writer = new PropelSchemaWriter(new Filesystem(), $this->getFixtureTargetDirectory());
        $merger = $this->createPropelSchemaMerger();

        $this->assertFalse(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_bar.schema.xml'));
        $schema = new PropelSchema($groupedFinder, $writer, $merger);
        $schema->copy();

        $this->assertTrue(file_exists($this->getFixtureTargetDirectory() . DIRECTORY_SEPARATOR . 'foo_bar.schema.xml'));
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface
     */
    protected function createPropelSchemaMerger(): PropelSchemaMergerInterface
    {
        return new PropelSchemaMerger(
            new PropelToUtilTextServiceBridge(
                $this->tester->getLocator()->utilText()->service()
            )
        );
    }
}
