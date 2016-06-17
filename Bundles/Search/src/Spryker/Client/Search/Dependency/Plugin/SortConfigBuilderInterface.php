<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Generated\Shared\Transfer\SortConfigTransfer;

interface SortConfigBuilderInterface
{

    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer);

    /**
     * @param string $parameterName
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    public function get($parameterName);

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    public function getAll();

    /**
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters);

    /**
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveSortDirection($requestParameters);

}
