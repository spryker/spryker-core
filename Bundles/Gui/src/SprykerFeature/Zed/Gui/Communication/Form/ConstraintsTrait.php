<?php

namespace SprykerFeature\Zed\Gui\Communication\Form;

use SprykerEngine\Zed\Kernel\Locator;

trait ConstraintsTrait
{

    public function __call($method, $parameters)
    {
        if (!preg_match('/^createConstraint/', $method)) {
            dump('not searching for constraints');die;
        }

        $locator = Locator::getInstance();

//        dump($locator);

//        dump(func_get_args());die;

    }

    protected function loadConstraint($name)
    {

    }

    public function createConstraintNotBlank2()
    {
        dump('a');die;
    }
}
