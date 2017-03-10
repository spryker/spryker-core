<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsGuiConfig extends AbstractBundleConfig
{

    const CMS_FOLDER_PATH = '@Cms/template/';

    /**
     * @return string|null
     */
    public function findYvesHost()
    {
        $config = $this->getConfig();

        $yvesHost = null;
        if ($config->hasKey(ApplicationConstants::BASE_URL_YVES)) {
            $yvesHost = $config->get(ApplicationConstants::BASE_URL_YVES);
        }
        // @deprecated This is just for backward compatibility
        if ($config->hasKey(ApplicationConstants::HOST_YVES)) {
            $yvesHost = $config->get(ApplicationConstants::HOST_YVES);
        }

        return $yvesHost;
    }

}
