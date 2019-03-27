<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Translator\Plugin\Validator;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface;
use Symfony\Component\Validator\ValidatorBuilderInterface;

class TranslatorValidatorPlugin implements ValidatorPluginInterface
{
    protected const SERVICE_TRANSLATOR = 'translator';
    protected const TRANSLATION_DOMAIN = 'validators';

    /**
     * {@inheritdoc}
     * - Sets translator for validator service;
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
        $validatorBuilder->setTranslator($container->get(static::SERVICE_TRANSLATOR));
        $validatorBuilder->setTranslationDomain(static::TRANSLATION_DOMAIN);

        return $validatorBuilder;
    }
}
