<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel\ClassResolver;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver as SharedAbstractClassResolver;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;

abstract class AbstractClassResolver extends SharedAbstractClassResolver
{

    const KEY_NAMESPACE = '%namespace%';
    const KEY_APPLICATION = '%application%';
    const KEY_BUNDLE = '%bundle%';
    const KEY_STORE = '%store%';

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    private $classInfo;

    /**
     * @param object|string $callerClass
     *
     * @return AbstractClassResolver
     */
    public function setCallerClass($callerClass)
    {
        $this->classInfo = new ClassInfo();
        $this->classInfo->setClass($callerClass);

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    public function getClassInfo()
    {
        return $this->classInfo;
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_APPLICATION => $this->getClassInfo()->getApplication(),
            self::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            self::KEY_STORE => $store,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }

}
