<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface;

class SortConfigBuilder extends AbstractPlugin implements SortConfigBuilderInterface
{
    public const DIRECTION_ASC = 'asc';
    public const DIRECTION_DESC = 'desc';

    public const DEFAULT_SORT_PARAM_KEY = 'sort';

    /**
     * @var \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    protected $sortConfigTransfers = [];

    /**
     * @var string
     */
    protected $sortParamKey;

    /**
     * @param string $sortParamName
     */
    public function __construct($sortParamName = self::DEFAULT_SORT_PARAM_KEY)
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
     * @param string $parameterName
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    public function get($parameterName)
    {
        if (isset($this->sortConfigTransfers[$parameterName])) {
            return $this->sortConfigTransfers[$parameterName];
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    public function getAll()
    {
        return $this->sortConfigTransfers;
    }

    /**
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters)
    {
        $sortParamName = array_key_exists($this->sortParamKey, $requestParameters) ? $requestParameters[$this->sortParamKey] : null;

        return $sortParamName;
    }

    /**
     * @param string $sortParamName
     *
     * @return string|null
     */
    public function getSortDirection($sortParamName)
    {
        $sortConfigTransfer = $this->get($sortParamName);

        if (!$sortConfigTransfer) {
            return null;
        }

        if ($sortConfigTransfer->getIsDescending()) {
            return self::DIRECTION_DESC;
        }

        return self::DIRECTION_ASC;
    }

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return void
     */
    protected function assertSortConfigTransfer(SortConfigTransfer $sortConfigTransfer)
    {
        $sortConfigTransfer
            ->requireName()
            ->requireParameterName()
            ->requireFieldName();
    }
}
