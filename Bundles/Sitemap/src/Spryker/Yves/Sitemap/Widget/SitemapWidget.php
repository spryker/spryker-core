<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Widget;

use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SitemapWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAM_SITEMAP_INDEX_FILE_NAME = 'sitemapIndexFileName';

    public function __construct()
    {
        $this->addParameter(static::PARAM_SITEMAP_INDEX_FILE_NAME, SitemapConstants::SITEMAP_INDEX_FILE_NAME);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SitemapWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@Sitemap/views/sitemap/sitemap.twig';
    }
}
