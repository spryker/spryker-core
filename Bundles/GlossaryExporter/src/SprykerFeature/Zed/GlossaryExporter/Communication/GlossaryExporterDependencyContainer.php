<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\GlossaryExporter\Communication;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\GlossaryExporter\Communication\Plugin\KeyBuilderPlugin;
use SprykerFeature\Zed\GlossaryExporter\Persistence\GlossaryExporterQueryContainerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

class GlossaryExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return KeyBuilderPlugin
     */
    public function getKeyBuilder()
    {
        return $this->getLocator()->glossaryExporter()->pluginKeyBuilderPlugin();
    }

    /**
     * @return GlossaryExporterQueryContainerInterface
     */
    public function getGlossaryQueryContainer()
    {
        return $this->getLocator()->glossaryExporter()->queryContainer();
    }
}
