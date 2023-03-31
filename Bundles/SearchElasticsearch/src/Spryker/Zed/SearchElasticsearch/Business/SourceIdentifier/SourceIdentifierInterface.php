<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier;

interface SourceIdentifierInterface
{
    /**
     * @param string $sourceIdentifier
     * @param string|null $storeName
     *
     * @throws \Spryker\Zed\SearchElasticsearch\Business\Exception\InvalidSourceIdentifierException
     *
     * @return string
     */
    public function translateToIndexName(string $sourceIdentifier, ?string $storeName): string;

    /**
     * @param string $sourceIdentifier
     * @param string|null $storeName
     *
     * @return bool
     */
    public function isSupported(string $sourceIdentifier, ?string $storeName): bool;

    /**
     * @param string $sourceIdentifier
     * @param string|null $storeName
     *
     * @return bool
     */
    public function isPrefixedWithStoreName(string $sourceIdentifier, ?string $storeName): bool;
}
