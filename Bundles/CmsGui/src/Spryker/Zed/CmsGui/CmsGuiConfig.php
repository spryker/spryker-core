<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\CmsGui\CmsGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsGuiConfig extends AbstractBundleConfig
{
    public const CMS_FOLDER_PATH = '@Cms/templates/';

    /**
     * @return string
     */
    public function getCmsFolderPath(): string
    {
        return $this->get(CmsGuiConstants::CMS_FOLDER_PATH, static::CMS_FOLDER_PATH);
    }

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

        return $yvesHost . sprintf($this->getConfig()->get(CmsGuiConstants::CMS_PAGE_PREVIEW_URI), $idCmsPage);
    }
}
