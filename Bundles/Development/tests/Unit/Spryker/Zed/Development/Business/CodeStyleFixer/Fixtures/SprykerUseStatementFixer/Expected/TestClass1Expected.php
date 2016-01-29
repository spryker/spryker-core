<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

use Spryker\Zed\Foo;
use Pyz\Zed\Foo\Bar\Baz;
use X\Y;
use Spryker\Zed\Development\Business\InstalledPackages\InstalledPackageFinder as InstalledPackagesInstalledPackageFinder;
use Foo\InstalledPackageFinder;

class TestClass1Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new Foo($x);
        new Baz($x);
    }

    public function replaceFunctionB()
    {
        new Foo($x);
    }

    protected function replaceFunctionC(\Foo\PackagesTransfer $collection, $path)
    {
        $x = new InstalledPackageFinder();
        $y = new InstalledPackagesInstalledPackageFinder();

        return new InstalledPackagesInstalledPackageFinder(
            $collection,
            $path
        );
    }

    public function replaceNotYetFunction()
    {
        Baz::x();
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
