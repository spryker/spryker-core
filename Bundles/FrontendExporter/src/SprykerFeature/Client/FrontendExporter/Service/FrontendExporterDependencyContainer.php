<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\FrontendExporter\Service;

use Generated\Client\Ide\FactoryAutoCompletion\FrontendExporter;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method FrontendExporter getFactory()
 */
class FrontendExporterDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     */
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
