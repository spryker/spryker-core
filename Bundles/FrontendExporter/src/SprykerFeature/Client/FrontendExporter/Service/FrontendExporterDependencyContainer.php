<?php

namespace SprykerFeature\Client\FrontendExporter\Service;

use Generated\Client\Ide\FactoryAutoCompletion\FrontendExporter;
use SprykerFeature\Yves\FrontendExporter\Business\Matcher\UrlMatcherInterface;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;

/**
 * @method FrontendExporter getFactory()
 */
class FrontendExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        $urlKeyBuilder = $this->getFactory()->createKeyBuilderUrlKeyBuilder();
        $kvReader = $this->getLocator()->kvStorage()->client();


        return $this->getFactory()->createMatcherUrlMatcher(
            $urlKeyBuilder,
            $kvReader
        );
    }
}
