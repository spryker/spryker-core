<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Finder\Finder;

abstract class AbstractAutoCompletion extends PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $this->baseDir = __DIR__ . '/Fixtures/';
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $testDirectory = $this->baseDir . 'test';
        if (!is_dir($testDirectory)) {
            mkdir($testDirectory, 0775, true);
        }
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $this->cleanUpTestDir();
    }

    /**
     * @return void
     */
    protected function cleanUpTestDir()
    {
        if ($this->baseDir . 'test/') {
            $finder = new Finder();
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($finder->files()->in($this->baseDir . 'test/') as $file) {
                unlink($file->getPathname());
            }
            rmdir($this->baseDir . 'test/');
        }
    }

}
