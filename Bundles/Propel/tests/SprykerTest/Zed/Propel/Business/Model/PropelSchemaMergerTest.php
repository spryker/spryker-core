<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMerger;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface;
use Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceBridge;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelSchemaMergerTest
 * Add your own group annotations below this line
 */
class PropelSchemaMergerTest extends Unit
{
    public const LEVEL_PROJECT = 'Project';
    public const LEVEL_VENDOR = 'Vendor';

    /**
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMergeTwoSchemaFilesMustReturnStringWithMergedContent()
    {
        $filesToMerge = [
            $this->getSplFileInfo('foo_bar.schema.xml', static::LEVEL_VENDOR),
            $this->getSplFileInfo('foo_bar.schema.xml', static::LEVEL_PROJECT),
        ];

        $merger = $this->createPropelSchemaMerger();
        $content = $merger->merge($filesToMerge);
        $expected = file_get_contents($this->getFixtureDirectory() . 'expected.merged.schema.xml');

        $this->assertSame($expected, $content);
    }

    /**
     * @return void
     */
    public function testMergeMoreThanTwoSchemaFilesMustReturnStringWithMergedContent()
    {
        $filesToMerge = [
            $this->getSplFileInfo('foo_bar.schema.xml', static::LEVEL_VENDOR),
            $this->getSplFileInfo('bar_foo.schema.xml', static::LEVEL_VENDOR),
            $this->getSplFileInfo('foo_bar.schema.xml', static::LEVEL_PROJECT),
        ];

        $merger = $this->createPropelSchemaMerger();
        $content = $merger->merge($filesToMerge);
        $expected = file_get_contents($this->getFixtureDirectory() . 'expected.merged.three.uniques.schema.xml');
        $this->assertSame($expected, $content);
    }

    /**
     * @return void
     */
    public function testMergeAllowsToChangeAttributeValue()
    {
        $filesToMerge = [
            $this->getSplFileInfo('attribute_value_change.schema.xml', static::LEVEL_VENDOR),
            $this->getSplFileInfo('attribute_value_change.schema.xml', static::LEVEL_PROJECT),
        ];

        $merger = $this->createPropelSchemaMerger();
        $content = $merger->merge($filesToMerge);

        $expected = file_get_contents($this->getFixtureDirectory() . 'expected.merged_attribute_change.schema.xml');
        $this->assertSame($expected, $content);
    }

    /**
     * @return void
     */
    public function testMergeSortsElements(): void
    {
        $filesToMerge = [
            $this->getSplFileInfo('to_sort_second.schema.xml', static::LEVEL_PROJECT),
            $this->getSplFileInfo('to_sort_first.schema.xml', static::LEVEL_PROJECT),
        ];

        $merger = $this->createPropelSchemaMerger();

        $content = $merger->merge($filesToMerge);
        $expected = file_get_contents($this->getFixtureDirectory() . 'expected.sorted.schema.xml');

        $this->assertSame($expected, $content);
    }

    /**
     * @param string $fileName
     * @param string $level
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    protected function getSplFileInfo($fileName, $level)
    {
        return new SplFileInfo($this->getFixtureDirectory($level) . $fileName, '', '');
    }

    /**
     * @param string|null $level
     *
     * @return string
     */
    private function getFixtureDirectory($level = null)
    {
        $pathParts = [
            __DIR__,
            'Fixtures',
            'PropelSchemaMerger',
        ];

        if ($level) {
            $pathParts[] = $level;
        }

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface
     */
    protected function createPropelSchemaMerger(): PropelSchemaMergerInterface
    {
        return new PropelSchemaMerger(
            new PropelToUtilTextServiceBridge(
                $this->tester->getLocator()->utilText()->service()
            ),
            new PropelConfig()
        );
    }
}
