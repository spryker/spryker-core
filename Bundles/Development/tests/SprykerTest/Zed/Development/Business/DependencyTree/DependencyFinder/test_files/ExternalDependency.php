<?php

namespace SprykerTest\Zed\Development\Business\DependencyTree\DependencyFinder\Fixtures;

use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\ExternalDependency as BaseExternalDependency;
use Company\MyClass as Something;
use Exception;

abstract class ExternalDependency
{

    /**
     * @throws \Exception
     * @throws \Symfony\Component\Finder\Finder
     * @return void
     */
    public function exceptionA()
    {
        throw new \Exception();
    }

    /**
     * @throws \Exception
     * @throws \Symfony\Component\Finder\Finder
     * @return void
     */
    public function exceptionB()
    {
        $className = \Symfony\Component\Config\Definition\Exception\Exception::class;

        throw new $className;
    }

    /**
     * @throws \Exception
     * @throws \Symfony\Component\Finder\Finder
     * @return void
     */
    public function method()
    {
        new \Symfony\Component\Finder\Finder2();
        \Symfony\Component\Finder\Finder3::class;
        new \ZendAPI_Job('foo');
        new BaseExternalDependency();
        (new \DateTime())->format(\DateTime::ATOM);
        $variable = [];
        $variable[\Company\SomeClassName::SOME_CONST][self::SOME_CONST];
    }

    /**
     * @param int $row
     * @param int $offset
     * @param string $indexType
     *
     * @return void
     */
    abstract public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM);

}
