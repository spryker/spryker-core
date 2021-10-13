<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueVolumePriceConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const MESSAGE = 'The set of inputs Store, Currency, and Quantity needs to be unique.';

    /**
     * @var \Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    protected $volumePriceExtractor;

    /**
     * @phpstan-param array<mixed> $options
     *
     * @param \Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface $volumePriceExtractor
     * @param array|null $options
     */
    public function __construct(
        VolumePriceExtractorInterface $volumePriceExtractor,
        ?array $options = null
    ) {
        $this->volumePriceExtractor = $volumePriceExtractor;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function getVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return $this->volumePriceExtractor;
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
