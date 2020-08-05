<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Translator\Plugin\Validator;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Validator\ValidatorBuilder;

class TranslatorValidatorPlugin extends AbstractPlugin implements ValidatorPluginInterface
{
    /**
     * @uses \Spryker\Yves\Translator\Plugin\Application\TranslatorApplicationPlugin::SERVICE_TRANSLATOR
     */
    protected const SERVICE_TRANSLATOR = 'translator';
    protected const TRANSLATION_DOMAIN = 'validators';

    /**
     * {@inheritDoc}
     * - Adds `translator`.
     *
     * @api
     *
     * @param \Symfony\Component\Validator\ValidatorBuilder $validatorBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilder
     */
    public function extend(ValidatorBuilder $validatorBuilder, ContainerInterface $container): ValidatorBuilder
    {
        $validatorBuilder->setTranslator($container->get(static::SERVICE_TRANSLATOR));
        $validatorBuilder->setTranslationDomain(static::TRANSLATION_DOMAIN);

        return $validatorBuilder;
    }
}
