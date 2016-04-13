<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;

class SortConfigBuilder extends AbstractPlugin implements SortConfigBuilderInterface
{

    const DIRECTION_ASC = 'asc';
    const DIRECTION_DESC = 'desc';

    const PARAM_SORT = 'sort';
    const PARAM_SORT_DIRECTION = 'sort_order';

    /**
     * @var \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    protected $sortConfigTransfers = [];

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
        $sortParamName = array_key_exists(self::PARAM_SORT, $requestParameters) ? $requestParameters[self::PARAM_SORT] : null;

        return $sortParamName;
    }

    /**
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveSortDirection($requestParameters)
    {
        $direction = array_key_exists(self::PARAM_SORT_DIRECTION, $requestParameters) ? $requestParameters[self::PARAM_SORT_DIRECTION] : self::DIRECTION_ASC;

        return $direction;
    }

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
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
