<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

class SearchConfig extends AbstractBundleConfig
{

    /**
     * @return AbstractInstallerPlugin[]
     */
    public function getInstaller()
    {
        return [
            $this->getLocator()->productSearch()->pluginInstaller(),
        ];
    }

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->get(SystemConfig::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

}
