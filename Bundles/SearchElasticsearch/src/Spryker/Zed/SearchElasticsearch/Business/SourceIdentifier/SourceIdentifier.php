<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier;

use Spryker\Zed\SearchElasticsearch\Business\Exception\InvalidSourceIdentifierException;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

class SourceIdentifier implements SourceIdentifierInterface
{
    protected const STORE_PREFIX_DELIMITER = '_';

    protected const INVALID_SOURCE_IDENTIFIER_MESSAGE_TEMPLATE = 'Provided source identifier `%s` is not supported or cannot be installed for store `%s`.';

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $searchElasticsearchConfig;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $searchElasticsearchConfig
     */
    public function __construct(SearchElasticsearchConfig $searchElasticsearchConfig)
    {
        $this->searchElasticsearchConfig = $searchElasticsearchConfig;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @throws \Spryker\Zed\SearchElasticsearch\Business\Exception\InvalidSourceIdentifierException
     *
     * @return string
     */
    public function translateToIndexName(string $sourceIdentifier): string
    {
        if (!$this->isSupported($sourceIdentifier)) {
            throw new InvalidSourceIdentifierException(
                sprintf(
                    static::INVALID_SOURCE_IDENTIFIER_MESSAGE_TEMPLATE,
                    $sourceIdentifier,
                    $this->getCurrentStore()
                )
            );
        }

        if ($this->isPrefixedWithStoreName($sourceIdentifier)) {
            return $this->buildIndexName($sourceIdentifier);
        }

        return $this->buildIndexName($sourceIdentifier, $this->getCurrentStore());
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return bool
     */
    public function isSupported(string $sourceIdentifier): bool
    {
        $configSourceIdentifier = $this->findMatchingConfigSourceIdentifier($sourceIdentifier);

        if ($configSourceIdentifier === null) {
            return false;
        }

        if ($sourceIdentifier === $configSourceIdentifier) {
            return true;
        }

        return mb_strtolower(
            $this->getCurrentStore() .
            static::STORE_PREFIX_DELIMITER .
            $configSourceIdentifier
        ) === $sourceIdentifier;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @throws \Spryker\Zed\SearchElasticsearch\Business\Exception\InvalidSourceIdentifierException
     *
     * @return bool
     */
    public function isPrefixedWithStoreName(string $sourceIdentifier): bool
    {
        $configSourceIdentifier = $this->findMatchingConfigSourceIdentifier($sourceIdentifier);

        if ($configSourceIdentifier === null) {
            throw new InvalidSourceIdentifierException(
                sprintf(
                    static::INVALID_SOURCE_IDENTIFIER_MESSAGE_TEMPLATE,
                    $sourceIdentifier,
                    $this->getCurrentStore()
                )
            );
        }

        return mb_strpos($sourceIdentifier, $this->findMatchingConfigSourceIdentifier($sourceIdentifier)) > 0;
    }

    /**
     * @param string $sourceIdentifier
     * @param string|null $currentStore
     *
     * @return string
     */
    protected function buildIndexName(string $sourceIdentifier, ?string $currentStore = null): string
    {
        $indexParameters = [
            $this->searchElasticsearchConfig->getIndexPrefix(),
            $currentStore,
            $sourceIdentifier,
        ];

        return mb_strtolower(implode('_', array_filter($indexParameters)));
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string|null
     */
    protected function findMatchingConfigSourceIdentifier(string $sourceIdentifier): ?string
    {
        $supportedSourceIdentifiers = $this->searchElasticsearchConfig->getSupportedSourceIdentifiers();

        foreach ($supportedSourceIdentifiers as $supportedSourceIdentifier) {
            if (preg_match(sprintf('/(.+%s)?%s$/', static::STORE_PREFIX_DELIMITER, $supportedSourceIdentifier), $sourceIdentifier)) {
                return $supportedSourceIdentifier;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getCurrentStore(): string
    {
        return APPLICATION_STORE;
    }
}
