<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class GlossaryConfig extends AbstractBundleConfig
{
    /**
     * Used as `item_type` for touch mechanism.
     *
     * @var string
     */
    public const RESOURCE_TYPE_TRANSLATION = 'translation';

    /**
     * @var string
     */
    protected const REDIRECT_URL_DEFAULT = '/glossary';

    /**
     * @api
     *
     * @return array<string>
     */
    public function getGlossaryFilePaths()
    {
        /** @var array<string> $sourceGlossary */
        $sourceGlossary = glob(APPLICATION_SOURCE_DIR . '/*/*/*/Resources/glossary.yml', GLOB_NOSORT);

        /** @var array<string> $vendorGlossary */
        $vendorGlossary = glob(APPLICATION_VENDOR_DIR . '/*/*/src/*/*/*/Resources/glossary.yml', GLOB_NOSORT);

        $paths = array_merge(
            $sourceGlossary,
            $vendorGlossary,
        );

        return $paths;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedirectUrl(): string
    {
        return static::REDIRECT_URL_DEFAULT;
    }
}
