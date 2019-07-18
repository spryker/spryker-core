<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\IdeAutoCompletion\Bundle;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractor;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Bundle
 * @group NamespaceExtractorTest
 * Add your own group annotations below this line
 */
class NamespaceExtractorTest extends Unit
{
    /**
     * @return void
     */
    public function testReplacementOfRegularBaseDirectory()
    {
        $baseDirectory = '/foo/bar/baz/Bundle/src/';
        $directory = new SplFileInfo('/foo/bar/baz/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespaceExtractor = new NamespaceExtractor();
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);

        $this->assertSame('Spryker\Application\Bundle', $namespace);
    }

    /**
     * @return void
     */
    public function testReplacementOfAsteriskGlobPatternBaseDirectory()
    {
        $baseDirectory = '/foo/bar/baz/*/src/';
        $directory = new SplFileInfo('/foo/bar/baz/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespaceExtractor = new NamespaceExtractor();
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);

        $this->assertSame('Spryker\Application\Bundle', $namespace);
    }

    /**
     * @return void
     */
    public function testReplacementOfQuestionMarkGlobPatternBaseDirectory()
    {
        $baseDirectory = '/foo/bar/?az/Bundle/src/';
        $directory = new SplFileInfo('/foo/bar/baz/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespaceExtractor = new NamespaceExtractor();
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);

        $this->assertSame('Spryker\Application\Bundle', $namespace);
    }

    /**
     * @return void
     */
    public function testReplacementOfBraceGlobPatternBaseDirectory()
    {
        $baseDirectory = '/foo/bar/{baz,spryker}/Bundle/src/';
        $namespaceExtractor = new NamespaceExtractor();

        $directory = new SplFileInfo('/foo/bar/baz/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);
        $this->assertSame('Spryker\Application\Bundle', $namespace);

        $directory = new SplFileInfo('/foo/bar/spryker/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);
        $this->assertSame('Spryker\Application\Bundle', $namespace);
    }

    /**
     * @return void
     */
    public function testReplacementOfCharacterClassGlobPatternBasePath()
    {
        $baseDirectory = '/foo/bar/[bf]az/Bundle/src/';
        $namespaceExtractor = new NamespaceExtractor();

        $directory = new SplFileInfo('/foo/bar/baz/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);
        $this->assertSame('Spryker\Application\Bundle', $namespace);

        $directory = new SplFileInfo('/foo/bar/faz/Bundle/src/Spryker/Application/Bundle', 'foo', 'bar');
        $namespace = $namespaceExtractor->fromDirectory($directory, $baseDirectory);
        $this->assertSame('Spryker\Application\Bundle', $namespace);
    }
}
