<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilGlob;

use Codeception\Test\Unit;
use Spryker\Service\UtilGlob\UtilGlobService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilGlob
 * @group UtilGlobServiceTest
 * Add your own group annotations below this line
 */
class UtilGlobServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilGlob\UtilGlobServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGlobFindsDirectories(): void
    {
        $rootDirectory = $this->tester->getVirtualDirectory(['foo' => ['bar' => ['baz' => []]]]);

        $pattern = $rootDirectory . '*/*/baz';
        $utilGlobService = new UtilGlobService();
        $globbedDirectories = $utilGlobService->glob($pattern);

        $this->assertCount(1, $globbedDirectories);
    }

    /**
     * @return void
     */
    public function testGlobFindsDirectoriesWithTrailingSlash(): void
    {
        $rootDirectory = $this->tester->getVirtualDirectory(['foo' => ['bar' => ['baz' => []]]]);

        $pattern = $rootDirectory . '*/*/baz/';

        $utilGlobService = new UtilGlobService();
        $globbedDirectories = $utilGlobService->glob($pattern);

        $this->assertCount(1, $globbedDirectories);
    }

    /**
     * @return void
     */
    public function testGlobReturnsEmptyArrayIfPatternWasNotMatched(): void
    {
        $utilGlobService = new UtilGlobService();
        $globbedDirectories = $utilGlobService->glob('/not/existing/path/pattern');

        $this->assertCount(0, $globbedDirectories);
    }
}
