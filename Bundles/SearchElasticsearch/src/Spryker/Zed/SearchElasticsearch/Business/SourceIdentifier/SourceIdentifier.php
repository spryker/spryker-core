<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier;

use Spryker\Zed\SearchElasticsearch\Business\Exception\InvalidSourceIdentifierException;

class SourceIdentifier implements SourceIdentifierInterface
{
    protected const STORE_PREFIX_DELIMITER = '_';

    /**
     * @var string[]
     */
    protected $supportedSourceIdentifiers;

    /**
     * @param string[] $supportedSourceIdentifiers
     */
    public function __construct(array $supportedSourceIdentifiers)
    {
        $this->supportedSourceIdentifiers = $supportedSourceIdentifiers;
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
                    'Provided source identifier `%s` is not supported or cannot be installed for store `%s`.',
                    $sourceIdentifier,
                    $this->getCurrentStore()
                )
            );
        }

        if ($this->isPrefixedWithStoreName($sourceIdentifier)) {
            return $sourceIdentifier;
        }

        return $this->buildIndexName($sourceIdentifier);
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return bool
     */
    public function isSupported(string $sourceIdentifier): bool
    {
        $configSourceIdentifier = $this->getMatchingConfigSourceIdentifier($sourceIdentifier);

        if (!$configSourceIdentifier) {
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
     * @return bool
     */
    public function isPrefixedWithStoreName(string $sourceIdentifier): bool
    {
        return mb_strpos($sourceIdentifier, $this->getMatchingConfigSourceIdentifier($sourceIdentifier)) > 0;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    protected function buildIndexName(string $sourceIdentifier): string
    {
        $indexName = sprintf(
            '%s_%s',
            $this->getCurrentStore(),
            $sourceIdentifier
        );

        return mb_strtolower($indexName);
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    protected function getMatchingConfigSourceIdentifier(string $sourceIdentifier): string
    {
        foreach ($this->supportedSourceIdentifiers as $supportedSourceIdentifier) {
            if (preg_match(sprintf('/(.+%s)?%s$/', static::STORE_PREFIX_DELIMITER, $supportedSourceIdentifier), $sourceIdentifier)) {
                return $supportedSourceIdentifier;
            }
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getCurrentStore(): string
    {
        return APPLICATION_STORE;
    }
}
