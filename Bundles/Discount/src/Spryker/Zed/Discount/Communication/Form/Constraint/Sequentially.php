<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraints\Composite;

/**
 * The class exists for BC reasons only.
 * Symfony\Component\Validator\Constraints\Sequentially is not supported in Symfony 4.
 */
class Sequentially extends Composite
{
    /**
     * @var array
     */
    public $constraints = [];

    /**
     * @return string
     */
    public function getDefaultOption(): string
    {
        return 'constraints';
    }

    /**
     * @return array<string>
     */
    public function getRequiredOptions(): array
    {
        return ['constraints'];
    }

    /**
     * @return string
     */
    protected function getCompositeOption(): string
    {
        return 'constraints';
    }

    /**
     * @return array<string>
     */
    public function getTargets(): array
    {
        return [static::CLASS_CONSTRAINT, static::PROPERTY_CONSTRAINT];
    }
}
