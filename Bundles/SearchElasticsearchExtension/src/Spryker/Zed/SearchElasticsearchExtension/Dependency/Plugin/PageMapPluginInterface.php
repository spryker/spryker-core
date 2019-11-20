<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface;

interface PageMapPluginInterface
{
    /**
     * Specification:
     * - Builds data set, which can be stored for search in Elasticsearch, from raw data.
     *
     * @api
     *
     * @param \Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $data, LocaleTransfer $locale): PageMapTransfer;

    /**
     * Specification:
     * - Returns the name of associated resource, for which mapping is performed.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;
}
