<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Creator;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\DecimalObject\Decimal;

class MessageCreator implements MessageCreatorInterface
{
    /**
     * @var string
     */
    public const GLOSSARY_KEY_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_PARAMETER_STOCK = '%stock%';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_PARAMETER_SKU = '%sku%';

    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function createItemIsNotAvailableMessage(Decimal $availability, string $sku): MessageTransfer
    {
        if ($availability->lessThanOrEquals(0)) {
            return $this->createAvailabilityEmptyMessage($sku);
        }

        return $this->createAvailabilityFailedMessage($availability, $sku);
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createAvailabilityFailedMessage(Decimal $availability, string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_AVAILABILITY_FAILED)
            ->setParameters([
                static::GLOSSARY_KEY_PARAMETER_STOCK => $availability->trim()->toString(),
                static::GLOSSARY_KEY_PARAMETER_SKU => $sku,
            ]);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createAvailabilityEmptyMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_AVAILABILITY_EMPTY)
            ->setParameters([
                static::GLOSSARY_KEY_PARAMETER_SKU => $sku,
            ]);
    }
}
