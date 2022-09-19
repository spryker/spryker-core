<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber;

use Generated\Shared\Transfer\NumberFormatConfigTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilNumber\UtilNumberServiceFactory getFactory()
 */
class UtilNumberService extends AbstractService implements UtilNumberServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer
     *
     * @return string
     */
    public function formatInt(NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer): string
    {
        return $this->getFactory()
            ->createNumberFormatter()
            ->formatInt($numberFormatIntRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer
     *
     * @return string
     */
    public function formatFloat(NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer): string
    {
        return $this->getFactory()
            ->createNumberFormatter()
            ->formatFloat($numberFormatFloatRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\NumberFormatConfigTransfer
     */
    public function getNumberFormatConfig(?string $locale = null): NumberFormatConfigTransfer
    {
        return $this->getFactory()
            ->createNumberFormatConfigurationProvider()
            ->getNumberFormatConfig($locale);
    }
}
