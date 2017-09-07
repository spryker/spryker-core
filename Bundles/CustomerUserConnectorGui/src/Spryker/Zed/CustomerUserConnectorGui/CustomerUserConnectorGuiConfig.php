<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\CustomerUserConnectorGui\CustomerUserConnectorGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerUserConnectorGuiConfig extends AbstractBundleConfig
{

    /**
     * @return string|null
     */
    public function findYvesHost()
    {
        $config = $this->getConfig();

        if ($config->hasKey(ApplicationConstants::BASE_URL_YVES)) {
            return $config->get(ApplicationConstants::BASE_URL_YVES);
        }

        // @deprecated This is just for backward compatibility
        if ($config->hasKey(ApplicationConstants::HOST_YVES)) {
            return $config->get(ApplicationConstants::HOST_YVES);
        }

        return null;
    }

    /**
     * @param int $idCmsPage
     *
     * @return string
     */
    public function getPreviewPageUrl($idCmsPage)
    {
        $yvesHost = $this->findYvesHost();

        if ($yvesHost === null) {
            return '';
        }

        return $yvesHost . sprintf($this->getConfig()->get(CustomerUserConnectorGuiConstants::CMS_PAGE_PREVIEW_URI), $idCmsPage);
    }

}
