<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CmsExporterBusiness;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\CmsExporter\Business\Builder\PageBuilderInterface;

/**
 * @method CmsExporterBusiness getFactory()
 */
class CmsExporterDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return PageBuilderInterface
     */
    public function createPageBuilder()
    {
        return $this->getFactory()->createBuilderPageBuilder(
            $this->createPageKeyBuilder()
        );
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function createPageKeyBuilder()
    {
        return $this->getFactory()->createBuilderPageResourceKeyBuilder();
    }

}
