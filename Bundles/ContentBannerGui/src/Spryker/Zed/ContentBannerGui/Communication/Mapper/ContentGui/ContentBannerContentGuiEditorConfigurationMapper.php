<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig;

class ContentBannerContentGuiEditorConfigurationMapper implements ContentBannerContentGuiEditorConfigurationMapperInterface
{
    /**
     * @var \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig
     */
    protected $contentBannerGuiConfig;

    /**
     * @param \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig $config
     */
    public function __construct(ContentBannerGuiConfig $config)
    {
        $this->contentBannerGuiConfig = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach ($this->contentBannerGuiConfig->getContentWidgetTemplates() as $templateIdentifier => $templateName) {
            $templates[] = (new ContentWidgetTemplateTransfer())
                ->setIdentifier($templateIdentifier)
                ->setName($templateName);
        }

        return $templates;
    }

    /**
     * @return string
     */
    public function getTwigFunctionTemplate(): string
    {
        return "{{ " . $this->contentBannerGuiConfig->getTwigFunctionName() . "('%KEY%', '%TEMPLATE%') }}";
    }
}
