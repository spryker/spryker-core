<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AvailabilityGui;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\AvailabilityGui\AvailabilityGuiServiceFactory getFactory()
 */
class AvailabilityGuiService extends AbstractService implements AvailabilityGuiServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $leftOperand
     * @param float $rightOperand
     *
     * @return int
     */
    public function isEqual(float $leftOperand, float $rightOperand): int
    {
        return $this->getFactory()
            ->createFloatCalculator()
            ->isEqual($leftOperand, $rightOperand);
    }
}
