<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector\Service;

use Generated\Client\Ide\FactoryAutoCompletion\Collector;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method Collector getFactory()
 */
class CollectorDependencyContainer extends AbstractServiceDependencyContainer
{

    public function createUrlMatcher()
    {
        $urlKeyBuilder = $this->getFactory()->createKeyBuilderUrlKeyBuilder();
        $kvReader = $this->getLocator()->storage()->client();

        return $this->getFactory()->createMatcherUrlMatcher(
            $urlKeyBuilder,
            $kvReader
        );
    }

}
