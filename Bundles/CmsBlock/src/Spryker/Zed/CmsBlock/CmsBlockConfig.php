<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock;

use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockConfig extends AbstractBundleConfig
{
    public const CMS_TWIG_TEMPLATE_PREFIX = '@CmsBlock';
    public const CMS_BLOCK_PLACEHOLDER_PATTERN = '/<!-- CMS_BLOCK_PLACEHOLDER : "[a-zA-Z0-9._-]*" -->/';
    public const CMS_BLOCK_PLACEHOLDER_VALUE_PATTERN = '/"([^"]+)"/';
    protected const THEME_NAME_DEFAULT = 'default';

    /**
     * @return string
     */
    public function getPlaceholderPattern()
    {
        return static::CMS_BLOCK_PLACEHOLDER_PATTERN;
    }

    /**
     * @return string
     */
    public function getPlaceholderValuePattern()
    {
        return static::CMS_BLOCK_PLACEHOLDER_VALUE_PATTERN;
    }

    /**
     * @param string $templateRelativePath
     *
     * @return array
     */
    public function getTemplateRealPaths($templateRelativePath)
    {
        $templatePaths = [];

        foreach ($this->getThemeNames() as $themeName) {
            $templatePaths[] = $this->getAbsolutePath($templateRelativePath, 'Shared', $themeName);
        }

        return $templatePaths;
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
            '%s/%s/%s/CmsBlock/Theme/%s%s',
            APPLICATION_SOURCE_DIR,
            $this->get(CmsBlockConstants::PROJECT_NAMESPACE),
            $twigLayer,
            $themeName,
            $templateRelativePath
        );
    }

    /**
     * @return array
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
     * @return string
     */
    protected function getThemeName(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getThemeNameDefault(): string
    {
        return static::THEME_NAME_DEFAULT;
    }
}
