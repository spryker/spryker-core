<?php

namespace SprykerTest\Zed\Development\Business\DependencyTree\DependencyFinder\Fixtures;

use Company\SomeClassName;
use DateTime;
use Exception;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\ExternalDependency as BaseExternalDependency;
use Symfony\Component\Config\Definition\Exception\Exception as SymfonyException;
use Symfony\Component\Finder\Finder2;
use Symfony\Component\Finder\Finder3;
use ZendAPI_Job;

abstract class ExternalDependency
{

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function exceptionA()
    {
        throw new Exception();
    }

    /**
     *
     * @return void
     */
    public function exceptionB()
    {
        $className = SymfonyException::class;

        throw new $className;
    }

    /**
     *
     * @return void
     */
    public function method()
    {
        new Finder2();
        Finder3::class;
        new ZendAPI_Job('foo');
        new BaseExternalDependency();
        (new DateTime())->format(DateTime::ATOM);
        $variable = [];
        $variable[SomeClassName::SOME_CONST][self::SOME_CONST];
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
