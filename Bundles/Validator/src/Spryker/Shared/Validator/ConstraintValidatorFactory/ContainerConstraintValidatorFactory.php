<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator\ConstraintValidatorFactory;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory as SymfonyConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class ContainerConstraintValidatorFactory extends SymfonyConstraintValidatorFactory
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var string[]
     */
    protected $serviceNames;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param array $serviceNames Validator service names
     */
    public function __construct(ContainerInterface $container, array $serviceNames = [])
    {
        parent::__construct();

        $this->container = $container;
        $this->serviceNames = $serviceNames;
    }

    /**
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $name = $constraint->validatedBy();

        if (isset($this->serviceNames[$name]) && $this->container->has($this->serviceNames[$name])) {
            return $this->container->get($this->serviceNames[$name]);
        }

        return parent::getInstance($constraint);
    }
}
