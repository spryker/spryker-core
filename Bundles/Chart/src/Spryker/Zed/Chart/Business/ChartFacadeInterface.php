<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Business;

interface ChartFacadeInterface
{
    /**
     * Specification:
     *  - Returns all available chart types
     *
     * @api
     *
     * @return string[]
     */
    public function getChartTypes(): array;

    /**
     * Specification:
     *  - Returns default chart type as configured in store
     *
     * @api
     *
     * @return string
     */
    public function getDefaultChartType(): string;
}
