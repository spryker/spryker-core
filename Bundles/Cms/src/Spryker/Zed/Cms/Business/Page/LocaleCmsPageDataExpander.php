<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

class LocaleCmsPageDataExpander implements LocaleCmsPageDataExpanderInterface
{
    /**
     * @var string
     */
    public const PARAM_URL = 'url';
    /**
     * @var string
     */
    public const PARAM_VALID_FROM = 'valid_from';
    /**
     * @var string
     */
    public const PARAM_VALID_TO = 'valid_to';
    /**
     * @var string
     */
    public const PARAM_IS_ACTIVE = 'is_active';
    /**
     * @var string
     */
    public const PARAM_ID = 'id';
    /**
     * @var string
     */
    public const PARAM_TEMPLATE = 'template';
    /**
     * @var string
     */
    public const PARAM_PLACEHOLDERS = 'placeholders';
    /**
     * @var string
     */
    public const PARAM_NAME = 'name';
    /**
     * @var string
     */
    public const PARAM_META_TITLE = 'meta_title';
    /**
     * @var string
     */
    public const PARAM_META_KEYWORDS = 'meta_keywords';
    /**
     * @var string
     */
    public const PARAM_META_DESCRIPTION = 'meta_description';

    /**
     * @var \Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface[]
     */
    protected $cmsPageDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface[] $cmsPageDataExpanderPlugins
     */
    public function __construct(array $cmsPageDataExpanderPlugins)
    {
        $this->cmsPageDataExpanderPlugins = $cmsPageDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $localeCmsPageDataTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function calculateFlattenedLocaleCmsPageData(LocaleCmsPageDataTransfer $localeCmsPageDataTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->expand($this->flattenLocaleCmsPageDataTransfer($localeCmsPageDataTransfer), $localeTransfer);
    }

    /**
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function expand(array $cmsPageData, LocaleTransfer $localeTransfer): array
    {
        foreach ($this->cmsPageDataExpanderPlugins as $cmsPageDataExpanderPlugin) {
            $cmsPageData = $cmsPageDataExpanderPlugin->expand($cmsPageData, $localeTransfer);
        }

        return $cmsPageData;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $localeCmsPageDataTransfer
     *
     * @return array
     */
    protected function flattenLocaleCmsPageDataTransfer(LocaleCmsPageDataTransfer $localeCmsPageDataTransfer): array
    {
        return [
            static::PARAM_URL => $localeCmsPageDataTransfer->getUrl(),
            static::PARAM_VALID_FROM => $localeCmsPageDataTransfer->getValidFrom(),
            static::PARAM_VALID_TO => $localeCmsPageDataTransfer->getValidTo(),
            static::PARAM_IS_ACTIVE => $localeCmsPageDataTransfer->getIsActive(),
            static::PARAM_ID => $localeCmsPageDataTransfer->getIdCmsPage(),
            static::PARAM_TEMPLATE => $localeCmsPageDataTransfer->getTemplatePath(),
            static::PARAM_PLACEHOLDERS => $localeCmsPageDataTransfer->getPlaceholders(),
            static::PARAM_NAME => $localeCmsPageDataTransfer->getName(),
            static::PARAM_META_TITLE => $localeCmsPageDataTransfer->getMetaTitle(),
            static::PARAM_META_KEYWORDS => $localeCmsPageDataTransfer->getMetaKeywords(),
            static::PARAM_META_DESCRIPTION => $localeCmsPageDataTransfer->getMetaDescription(),
        ];
    }
}
