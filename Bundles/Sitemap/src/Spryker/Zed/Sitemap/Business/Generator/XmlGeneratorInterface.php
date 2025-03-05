<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap\Business\Generator;

interface XmlGeneratorInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\SitemapUrlTransfer> $sitemapUrlTransfers
     * @param array<array<int, \Generated\Shared\Transfer\SitemapUrlTransfer>> $sitemapUrlTransfersGroupedByIdEntity
     * @param string $yvesHost
     *
     * @return string
     */
    public function generateSitemapXmlContent(
        array $sitemapUrlTransfers,
        array $sitemapUrlTransfersGroupedByIdEntity,
        string $yvesHost
    ): string;

    /**
     * @param array<string> $sitemapFileNames
     * @param string $yvesHost
     *
     * @return string
     */
    public function generateSitemapIndexXmlContent(array $sitemapFileNames, string $yvesHost): string;
}
