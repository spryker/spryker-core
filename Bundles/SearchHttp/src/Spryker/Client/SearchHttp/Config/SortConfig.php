<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

use Generated\Shared\Transfer\SortConfigTransfer;

class SortConfig implements SortConfigInterface
{
    /**
     * @var string
     */
    public const DIRECTION_ASC = 'asc';

    /**
     * @var string
     */
    public const DIRECTION_DESC = 'desc';

    /**
     * @var string
     */
    public const DEFAULT_SORT_PARAM_KEY = 'sort';

    /**
     * @var string
     */
    public const ASC_DESC_PATTERN = '/(_asc|_desc)$/';

    /**
     * @var array<\Generated\Shared\Transfer\SortConfigTransfer>
     */
    protected array $sortConfigTransfers = [];

    /**
     * @var string
     */
    protected string $sortParamKey;

    /**
     * @param string $sortParamName
     */
    public function __construct(string $sortParamName = self::DEFAULT_SORT_PARAM_KEY)
    {
        $this->sortParamKey = $sortParamName;
    }

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer)
    {
        $this->assertSortConfigTransfer($sortConfigTransfer);

        $this->sortConfigTransfers[$sortConfigTransfer->getParameterName()] = $sortConfigTransfer;

        return $this;
    }

    /**
     * @return array<\Generated\Shared\Transfer\SortConfigTransfer>
     */
    public function getAll(): array
    {
        return $this->sortConfigTransfers;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters): ?string
    {
        return $requestParameters[$this->sortParamKey] ?? null;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return string
     */
    public function getActiveCleanedParamName(array $requestParameters): string
    {
        $sortConfigTransfer = $this->getActiveConfig($requestParameters);

        if (!$sortConfigTransfer) {
            /** @var \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer */
            $sortConfigTransfer = reset($this->sortConfigTransfers);
        }

        return $this->cleanSuffix($sortConfigTransfer->getParameterNameOrFail());
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return string
     */
    public function getSortDirection(array $requestParameters): string
    {
        $sortConfigTransfer = $this->getActiveConfig($requestParameters);

        if ($sortConfigTransfer && $sortConfigTransfer->getIsDescending()) {
            return static::DIRECTION_DESC;
        }

        return static::DIRECTION_ASC;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    protected function getActiveConfig(array $requestParameters): ?SortConfigTransfer
    {
        $activeParam = $requestParameters[$this->sortParamKey] ?? null;

        if (!$activeParam) {
            return null;
        }

        if (!isset($this->sortConfigTransfers[$activeParam])) {
            return null;
        }

        return $this->sortConfigTransfers[$activeParam];
    }

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return void
     */
    protected function assertSortConfigTransfer(SortConfigTransfer $sortConfigTransfer): void
    {
        $sortConfigTransfer
            ->requireName()
            ->requireParameterName()
            ->requireFieldName();
    }

    /**
     * @param string $parameterName
     *
     * @return string
     */
    protected function cleanSuffix(string $parameterName): string
    {
        return preg_replace(static::ASC_DESC_PATTERN, '', $parameterName) ?? $parameterName;
    }
}
