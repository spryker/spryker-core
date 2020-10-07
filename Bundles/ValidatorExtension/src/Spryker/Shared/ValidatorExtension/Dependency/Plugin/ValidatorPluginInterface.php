<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ValidatorExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Validator\ValidatorBuilder;

interface ValidatorPluginInterface
{
    /**
     * Specification:
     * - Extends `validator` service.
     *
     * @api
     *
     * @param \Symfony\Component\Validator\ValidatorBuilder $validatorBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilder
     */
    public function extend(ValidatorBuilder $validatorBuilder, ContainerInterface $container): ValidatorBuilder;
}
