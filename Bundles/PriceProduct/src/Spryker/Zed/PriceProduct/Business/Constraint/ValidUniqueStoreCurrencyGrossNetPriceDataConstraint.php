<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Constraint;

use Spryker\Zed\PriceProduct\Business\Model\ReaderInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidUniqueStoreCurrencyGrossNetPriceDataConstraint extends SymfonyConstraint
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    protected $reader;

    protected const MESSAGE = 'Data is duplicated';

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface $reader
     * @param array|null $options
     */
    public function __construct(ReaderInterface $reader, $options = null)
    {
        $this->reader = $reader;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    public function getReader(): ReaderInterface
    {
        return $this->reader;
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
