<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class QueryContainerResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%3$s\\Persistence\\%2$sQueryContainer';

    /**
     * @param object|string $callerClass
     *
     * @throws QueryContainerNotFoundException
     *
     * @return AbstractQueryContainer
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new QueryContainerNotFoundException($this->getClassInfo());
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_STORE
        );
    }

}
