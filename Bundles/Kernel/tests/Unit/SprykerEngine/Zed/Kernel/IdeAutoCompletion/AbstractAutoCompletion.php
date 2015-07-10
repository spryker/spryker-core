<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractAutoCompletion extends \PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $this->baseDir = __DIR__ . '/Fixtures/';
    }

    protected function cleanUpTestDir()
    {
        if ($this->baseDir . 'test/') {
            $finder = new Finder();
            /** @var SplFileInfo $file */
            foreach ($finder->files()->in($this->baseDir . 'test/') as $file) {
                unlink($file->getPathname());
            }
            rmdir($this->baseDir . 'test/');
        }
    }

}
