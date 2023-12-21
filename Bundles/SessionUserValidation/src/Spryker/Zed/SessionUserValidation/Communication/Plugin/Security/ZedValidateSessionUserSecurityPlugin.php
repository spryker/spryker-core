<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SessionUserValidation\Communication\SessionUserValidationCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionUserValidation\SessionUserValidationConfig getConfig()
 */
class ZedValidateSessionUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extends security service user session validator listener.
     * - Executes stack of {@link \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface} plugins.
     *
     * @api
     *
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        return $this->getFactory()->createSecurityServiceExtender()->extend($securityBuilder, $container);
    }
}
