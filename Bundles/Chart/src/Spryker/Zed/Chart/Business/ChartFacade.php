<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Chart\Business\ChartBusinessFactory getFactory()
 */
class ChartFacade extends AbstractFacade implements ChartFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getChartTypes(): array
    {
        return $this->getFactory()
            ->getConfig()
            ->getChartTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDefaultChartType(): string
    {
        return $this->getFactory()
            ->getConfig()
            ->getDefaultChartType();
    }
}
