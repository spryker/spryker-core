<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint;

use Symfony\Component\Validator\Constraints\Composite;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class TransferConstraint extends Composite
{
    /**
     * @var string
     */
    protected const FIELDS = 'fields';

    /**
     * @var string
     */
    protected const MISSING_FIELD_MESSAGE = 'This field is missing.';

    /**
     * @var array<mixed>
     */
    public $fields = [];

    /**
     * @param array<\Symfony\Component\Validator\Constraint>|null $options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $options = [static::FIELDS => $options];
        }

        parent::__construct($options);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     *
     * @return void
     */
    protected function initializeNestedConstraints(): void
    {
        parent::initializeNestedConstraints();

        if (!is_array($this->fields)) {
            throw new ConstraintDefinitionException(sprintf('The option "%s" is expected to be an array in constraint "%s".', static::FIELDS, static::class));
        }
    }

    /**
     * @return array<string>
     */
    public function getRequiredOptions(): array
    {
        return [static::FIELDS];
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function getMissingFieldsMessage(): string
    {
        return static::MISSING_FIELD_MESSAGE;
    }

    /**
     * @return string
     */
    protected function getCompositeOption(): string
    {
        return static::FIELDS;
    }
}
