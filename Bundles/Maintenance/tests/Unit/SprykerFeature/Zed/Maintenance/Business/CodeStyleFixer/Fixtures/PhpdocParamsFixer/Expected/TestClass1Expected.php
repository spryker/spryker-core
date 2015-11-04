<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\PhpdocParamsFixer\Input;

class TestClass1Input
{

    /**
     * @param int $foo
     * @param float $bar
     *
     * @return void
     */
    public function replaceFunction($foo, $bar)
    {
    }

    /**
     * Description
     * over two lines
     *
     * @param int $foo Foo
     *
     * @return void
     */
    public function doNotReplaceFunction()
    {
    }

    /**
     * @param InputInterface $input Input
     * @param OutputInterface $output Output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function replaceComplex() {
    }

}
