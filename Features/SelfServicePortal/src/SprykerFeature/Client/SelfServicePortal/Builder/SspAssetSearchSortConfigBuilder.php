<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Builder;

use Generated\Shared\Transfer\SortConfigTransfer;

class SspAssetSearchSortConfigBuilder implements SortConfigBuilderInterface
{
    /**
     * @var string
     */
    protected const DIRECTION_ASC = 'asc';

    /**
     * @var string
     */
    protected const DIRECTION_DESC = 'desc';

    /**
     * @var string
     */
    protected const DEFAULT_SORT_PARAM_KEY = 'sort';

    /**
     * @var array<string, \Generated\Shared\Transfer\SortConfigTransfer>
     */
    protected array $sortConfigTransfers = [];

    /**
     * @var string
     */
    protected string $sortParamKey;

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

        $this->sortConfigTransfers[$sortConfigTransfer->getParameterNameOrFail()] = $sortConfigTransfer;

        return $this;
    }

    public function getSortConfigTransfer(?string $parameterName): ?SortConfigTransfer
    {
        return $this->sortConfigTransfers[$parameterName] ?? null;
    }

    /**
     * @return array<\Generated\Shared\Transfer\SortConfigTransfer>
     */
    public function getAllSortConfigTransfers(): array
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

    public function getSortDirection(?string $sortParamName): ?string
    {
        if (!$sortParamName) {
            return null;
        }
        $sortConfigTransfer = $this->getSortConfigTransfer($sortParamName);

        if (!$sortConfigTransfer) {
            return null;
        }

        if ($sortConfigTransfer->getIsDescending()) {
            return static::DIRECTION_DESC;
        }

        return static::DIRECTION_ASC;
    }

    protected function assertSortConfigTransfer(SortConfigTransfer $sortConfigTransfer): void
    {
        $sortConfigTransfer
            ->requireName()
            ->requireParameterName()
            ->requireFieldName();
    }
}
