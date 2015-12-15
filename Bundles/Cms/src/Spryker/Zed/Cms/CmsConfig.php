<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Application\ApplicationConstants;

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
        $physicalAddress = APPLICATION_ROOT_DIR . '/src/' . $this->get(ApplicationConstants::PROJECT_NAMESPACE) . '/Yves/Cms/Theme/' . $this->get(ApplicationConstants::YVES_THEME) . $templateRelativePath;

        return $physicalAddress;
    }

}
