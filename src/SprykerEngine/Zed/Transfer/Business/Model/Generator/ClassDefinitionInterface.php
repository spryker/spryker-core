<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface ClassDefinitionInterface
{
    public function setInterface($implementsInterface);
    public function setProperty(array $properties);
    public function setClassName($name);
    public function getClassName();
    public function getInterfaces();
    public function getProperties();
}
