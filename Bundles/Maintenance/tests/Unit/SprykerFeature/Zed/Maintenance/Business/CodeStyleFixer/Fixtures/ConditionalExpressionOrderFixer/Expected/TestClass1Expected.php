<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\ConditionalExpressionOrderFixer\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replace() {
        if ($foo === null) {
        }
        $foo = 2/($foo === 2);
        if ($foo === true) {
        }
        if ($foo > 2) {
        }
        if ($foo < 2) {
        }
        if ($redirectData[self::FROM_URL] !== null) {
        }
        $foo = $foo == 2;
        $foo = $foo === 3;
        if ($foo === null && $this->foo() === false) {
        }
        if ($this->foo() >= 2) {
        }
        if ($this->foo() <= 2) {
        }
        if (array_key_exists($fromXmlElementName, $toXmlElements) === true) {
        }
        if (($results instanceof ObjectCollection) === false) {
        }
        if ($taxSetTransfer->getTaxRates()->count() === 0) {
        }
        if ($row['sku_product_concrete'] !== true) {
        }
        if (isset($name[0]) && $name[0] === '@') {
        }
        return $xyz !== null;
    }

    /**
     * @return void
     */
    public function replaceNotYet()
    {
        if (MyClass::CONSTANT === $foo) {
        }
    }

    /**
     * @return void
     */
    public function doNotReplace()
    {
        $foo = $foo == 2;
        $foo = $foo === 2;
        if ($foo === true) {
        }
        if ($foo === null && $this->foo() === false) {
        }
    }

}
