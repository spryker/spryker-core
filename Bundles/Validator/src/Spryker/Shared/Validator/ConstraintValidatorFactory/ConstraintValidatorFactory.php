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

class ConstraintValidatorFactory extends SymfonyConstraintValidatorFactory
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[]
     */
    protected $constraintPlugins = [];

    /**
     * @var \Symfony\Component\Validator\ConstraintValidatorInterface[]
     */
    protected $constraintInstances = [];

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[] $constraintPlugins
     */
    public function __construct(ContainerInterface $container, array $constraintPlugins = [])
    {
        parent::__construct();

        $this->container = $container;
        $this->registerConstraintPlugins($constraintPlugins);
    }

    /**
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $name = $constraint->validatedBy();

        $constraintInstance = $this->findConstraintInstance($name);
        if ($constraintInstance) {
            return $constraintInstance;
        }

        return parent::getInstance($constraint);
    }

    /**
     * @param \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[] $constraintPlugins
     *
     * @return void
     */
    protected function registerConstraintPlugins(array $constraintPlugins): void
    {
        foreach ($constraintPlugins as $constraintPlugin) {
            $this->constraintPlugins[$constraintPlugin->getName()] = $constraintPlugin;
        }
    }

    /**
     * @param string $constraintName
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface|null
     */
    protected function findConstraintInstance(string $constraintName): ?ConstraintValidatorInterface
    {
        if (isset($this->constraintInstances[$constraintName])) {
            return $this->constraintInstances[$constraintName];
        }

        if (isset($this->constraintPlugins[$constraintName])) {
            $constraintInstance = $this->constraintPlugins[$constraintName]->getConstraintInstance($this->container);
            $this->constraintInstances[$constraintName] = $constraintInstance;

            return $constraintInstance;
        }

        return null;
    }
}
