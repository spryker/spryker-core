<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;

class CmsPageUrlBuilder implements CmsPageUrlBuilderInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer)
    {
        $cmsPageAttributesTransfer->requireUrl()
            ->requireLocaleName();

        $prefix = $this->getPageUrlPrefix($cmsPageAttributesTransfer->getLocaleName());

        $url = $cmsPageAttributesTransfer->getUrl();
        if (preg_match('#^' . $prefix . '#i', $url) > 0) {
            return $url;
        }

        $url = preg_replace('#^/#', '', $url);

        $urlWithLanguageCode = $prefix . $url;

        return $urlWithLanguageCode;
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    public function getPageUrlPrefix($localeName)
    {
        return '/' . $this->extractLanguageCode($localeName) . '/';
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    protected function extractLanguageCode($localeName)
    {
        $localeNameParts = explode('_', $localeName);
        $languageCode = $localeNameParts[0];

        return $languageCode;
    }

}
