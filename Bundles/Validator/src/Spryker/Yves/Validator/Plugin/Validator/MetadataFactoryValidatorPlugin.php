<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator\Plugin\Validator;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Validator\ValidatorBuilderInterface;

/**
 * @method \Spryker\Yves\Validator\ValidatorFactory getFactory()
 */
class MetadataFactoryValidatorPlugin extends AbstractPlugin implements ValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds metadata factory.
     *
     * @api
     *
     * @param \Symfony\Component\Validator\ValidatorBuilderInterface $validatorBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilderInterface
     */
    public function extend(ValidatorBuilderInterface $validatorBuilder, ContainerInterface $container): ValidatorBuilderInterface
    {
        $validatorBuilder->setMetadataFactory($this->getFactory()->createValidatorMappingMetadataFactory());

        return $validatorBuilder;
    }
}
