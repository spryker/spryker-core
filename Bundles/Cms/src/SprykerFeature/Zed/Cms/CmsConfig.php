<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;

class CmsConfig extends AbstractBundleConfig
{

    /**
     * @param string $templateRelativePath
     *
     * @return string
     */
    public function getTemplateRealPath($templateRelativePath)
    {
        $templateRelativePath = substr($templateRelativePath, 4);
        $physicalAddress = APPLICATION_ROOT_DIR . '/src/' . $this->get(SystemConfig::PROJECT_NAMESPACE) . '/Yves/Cms/Theme/' . $this->get(YvesConfig::YVES_THEME) . $templateRelativePath;

        return $physicalAddress;
    }

}
