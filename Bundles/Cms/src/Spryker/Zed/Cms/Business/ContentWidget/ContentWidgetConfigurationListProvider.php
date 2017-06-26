<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\ContentWidget;

use Generated\Shared\Transfer\CmsContentWidgetConfigurationListTransfer;
use Generated\Shared\Transfer\CmsContentWidgetConfigurationTransfer;

class ContentWidgetConfigurationListProvider implements ContentWidgetConfigurationListProviderInterface
{

    /**
     * @var array|\Spryker\Shared\Cms\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface[]
     */
    protected $contentWidgetConfigurationProviders = [];

    /**
     * @param array|\Spryker\Shared\Cms\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface[] $contentWidgetConfigurationProviders
     */
    public function __construct($contentWidgetConfigurationProviders)
    {
        $this->contentWidgetConfigurationProviders = $contentWidgetConfigurationProviders;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsContentWidgetConfigurationListTransfer
     */
    public function getContentWidgetConfigurationList()
    {
        $cmsContentConfigurationList = new CmsContentWidgetConfigurationListTransfer();
        foreach ($this->contentWidgetConfigurationProviders as $contentWidgetConfigurationProvider) {

            $cmsContentWidgetConfigurationTransfer = $this->mapCmsContentWidgetConfigurationTransfer(
                $contentWidgetConfigurationProvider->getFunctionName(),
                $contentWidgetConfigurationProvider
            );
            $cmsContentConfigurationList->addCmsContentWidgetConfiguration($cmsContentWidgetConfigurationTransfer);
        }

        return $cmsContentConfigurationList;
    }

    /**
     * @param string $functionName
     * @param \Spryker\Shared\Cms\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface $contentWidgetConfigurationProvider
     *
     * @return \Generated\Shared\Transfer\CmsContentWidgetConfigurationTransfer
     */
    protected function mapCmsContentWidgetConfigurationTransfer($functionName, $contentWidgetConfigurationProvider)
    {
        $cmsContentWidgetConfigurationTransfer = new CmsContentWidgetConfigurationTransfer();
        $cmsContentWidgetConfigurationTransfer->setFunctionName($functionName);
        $cmsContentWidgetConfigurationTransfer->setTemplates($contentWidgetConfigurationProvider->getAvailableTemplates());
        $cmsContentWidgetConfigurationTransfer->setUsageInformation($contentWidgetConfigurationProvider->getUsageInformation());

        return $cmsContentWidgetConfigurationTransfer;
    }

}
