<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator\Constraint;

use Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueStoreCurrencyVolumeQuantityConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'The set of Store, Currency, and Quantity needs to be unique.';

    /**
     * @var \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface
     */
    protected $priceProductOfferVolumeService;

    /**
     * @phpstan-param array<mixed> $options
     *
     * @param \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface $priceProductOfferVolumeService
     * @param array|null $options
     */
    public function __construct(
        PriceProductOfferVolumeServiceInterface $priceProductOfferVolumeService,
        ?array $options = null
    ) {
        $this->priceProductOfferVolumeService = $priceProductOfferVolumeService;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface
     */
    public function getPriceProductOfferVolumeService(): PriceProductOfferVolumeServiceInterface
    {
        return $this->priceProductOfferVolumeService;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
