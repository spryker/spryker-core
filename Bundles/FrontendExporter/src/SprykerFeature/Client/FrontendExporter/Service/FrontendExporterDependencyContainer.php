<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\FrontendExporter\Service;

use Generated\Client\Ide\FactoryAutoCompletion\FrontendExporter;
use SprykerFeature\Yves\FrontendExporter\Business\Matcher\UrlMatcherInterface;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method FrontendExporter getFactory()
 */
class FrontendExporterDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return UrlMatcherInterface
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
