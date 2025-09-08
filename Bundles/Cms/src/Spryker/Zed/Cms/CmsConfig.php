<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms;

use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const CMS_TWIG_TEMPLATE_PREFIX = '@Cms';

    /**
     * @var string
     */
    protected const CMS_PLACEHOLDER_PATTERN = '/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9._-]*" -->/';

    /**
     * @var string
     */
    protected const CMS_PLACEHOLDER_VALUE_PATTERN = '/"([^"]+)"/';

    /**
     * @var string
     */
    protected const THEME_NAME_DEFAULT = 'default';

    /**
     * @var int
     */
    protected const DEFAULT_CMS_PAGE_EXPORT_CHUNK_SIZE = 1000;

    /**
     * @var int
     */
    protected const DEFAULT_CMS_PAGE_MESSAGE_BROKER_CHUNK_SIZE = 200;

    /**
     * @uses \Spryker\Shared\KernelApp\KernelAppConstants::TENANT_IDENTIFIER
     *
     * @var string
     */
    protected const KERNEL_APP_TENANT_IDENTIFIER = 'KERNEL_APP:TENANT_IDENTIFIER';

    /**
     * @api
     *
     * @return string
     */
    public function getPlaceholderPattern(): string
    {
        return static::CMS_PLACEHOLDER_PATTERN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPlaceholderValuePattern(): string
    {
        return static::CMS_PLACEHOLDER_VALUE_PATTERN;
    }

    /**
     * @api
     *
     * @deprecated Use {@link getTemplateRealPaths()} instead.
     *
     * @param string $templateRelativePath
     *
     * @return string
     */
    public function getTemplateRealPath($templateRelativePath)
    {
        return $this->getAbsolutePath($templateRelativePath, 'Yves');
    }

    /**
     * @api
     *
     * @param string $templateRelativePath
     *
     * @return array<string>
     */
    public function getTemplateRealPaths(string $templateRelativePath): array
    {
        $templatePaths = [];

        foreach ($this->getThemeNames() as $themeName) {
            $templatePaths[] = $this->getAbsolutePath($templateRelativePath, 'Yves', $themeName);
            $templatePaths[] = $this->getAbsolutePath($templateRelativePath, 'Shared', $themeName);
        }

        return $templatePaths;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function appendPrefixToCmsPageUrl(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(CmsConstants::TENANT_IDENTIFIER, $this->get(static::KERNEL_APP_TENANT_IDENTIFIER) ?? '');
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCmsPageExportChunkSize(): int
    {
        return static::DEFAULT_CMS_PAGE_EXPORT_CHUNK_SIZE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCmsPageMessageBrokerChunkSize(): int
    {
        return static::DEFAULT_CMS_PAGE_MESSAGE_BROKER_CHUNK_SIZE;
    }

    /**
     * @param string $templateRelativePath
     * @param string $twigLayer
     * @param string $themeName
     *
     * @return string
     */
    protected function getAbsolutePath(string $templateRelativePath, string $twigLayer, string $themeName = self::THEME_NAME_DEFAULT): string
    {
        $templateRelativePath = str_replace(static::CMS_TWIG_TEMPLATE_PREFIX, '', $templateRelativePath);

        return sprintf(
            '%s/%s/%s/Cms/Theme/%s%s',
            APPLICATION_SOURCE_DIR,
            $this->get(CmsConstants::PROJECT_NAMESPACE),
            $twigLayer,
            $themeName,
            $templateRelativePath,
        );
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getThemeNames(): array
    {
        if ($this->getThemeName() === '' || $this->getThemeName() === $this->getThemeNameDefault()) {
            return [
                $this->getThemeNameDefault(),
            ];
        }

        return [
            $this->getThemeName(),
            $this->getThemeNameDefault(),
        ];
    }

    /**
     * Specification:
     * - Defines if full locale name in URL is enabled.
     * - Full locale name in URL will be used instead of language code.
     *
     * @api
     *
     * @return bool
     */
    public function isFullLocaleNamesInUrlEnabled(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    protected function getThemeName(): string
    {
        return $this->get(CmsConstants::YVES_THEME, '');
    }

    /**
     * @return string
     */
    protected function getThemeNameDefault(): string
    {
        return static::THEME_NAME_DEFAULT;
    }
}
