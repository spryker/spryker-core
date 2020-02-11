<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ValidatorExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

interface ConstraintPluginInterface
{
    /**
     * Specification:
     * - Returns a constraint name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Returns a constraint instance.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getConstraintInstance(ContainerInterface $container): ConstraintValidatorInterface;
}
