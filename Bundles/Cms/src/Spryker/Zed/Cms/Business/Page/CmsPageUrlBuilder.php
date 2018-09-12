<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Spryker\Zed\Cms\CmsConfig;

class CmsPageUrlBuilder implements CmsPageUrlBuilderInterface
{
    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(CmsConfig $cmsConfig)
    {
        $this->cmsConfig = $cmsConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer)
    {
        $cmsPageAttributesTransfer->requireUrl()
            ->requireLocaleName();

        $prefix = $this->getPageUrlPrefix($cmsPageAttributesTransfer);

        if (!$prefix) {
            return $cmsPageAttributesTransfer->getUrl();
        }

        $url = $cmsPageAttributesTransfer->getUrl();
        if (preg_match('#^' . $prefix . '#i', $url) > 0) {
            return $url;
        }

        $url = preg_replace('#^/#', '', $url);

        $urlWithPrefix = $prefix . $url;

        return $urlWithPrefix;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function getPageUrlPrefix(CmsPageAttributesTransfer $cmsPageAttributesTransfer)
    {
        if (!$this->cmsConfig->appendPrefixToCmsPageUrl()) {
            return '';
        }

        $cmsPageAttributesTransfer->requireLocaleName();

        return '/' . $this->extractLanguageCode($cmsPageAttributesTransfer->getLocaleName()) . '/';
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
