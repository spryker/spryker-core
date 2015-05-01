<?php


namespace SprykerFeature\Zed\Url\Persistence\Propel;


use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;

class ResourceAwareSpyUrlTableMap extends SpyUrlTableMap
{
    /**
     * @return array
     */
    public static function getResourceColumnNames()
    {
        $reflection = new \ReflectionClass('SprykerFeature\\Zed\\Url\\Persistence\\Propel\\Map\\SpyUrlTableMap');
        $constants = $reflection->getConstants();

        return array_filter($constants, function ($constant) {
            return strpos($constant, 'fk_resource');
        });
    }

    public static function getConstantValue($constantName)
    {
        $reflection = new \ReflectionClass('SprykerFeature\\Zed\\Url\\Persistence\\Propel\\Map\\SpyUrlTableMap');
        return $reflection->getConstant($constantName);
    }
}
