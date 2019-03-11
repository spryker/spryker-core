<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber;

use Generated\Shared\Transfer\RounderTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilNumber\UtilNumberServiceFactory getFactory()
 */
class UtilNumberService extends AbstractService implements UtilNumberServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return float
     */
    public function round(RounderTransfer $rounderTransfer): float
    {
        return $this->getFactory()
            ->createFloatRounder()
            ->round($rounderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return int
     */
    public function roundToInt(RounderTransfer $rounderTransfer): int
    {
        return $this->getFactory()
            ->createFloatRounder()
            ->roundToInt($rounderTransfer);
    }
}
