<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use {@link \SprykerShop\Yves\SessionCustomerValidationPage\Plugin\Security\SaveCustomerSessionSecurityPlugin} instead.
 *
 * @method \Spryker\Yves\SessionCustomerValidation\SessionCustomerValidationFactory getFactory()
 */
class SaveSessionCustomerSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `SaveSessionCustomerListener` to event dispatcher.
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
        $securityBuilder = $this->addEventSubscriber($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addEventSubscriber(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addEventSubscriber(function () {
            return $this->getFactory()->createSaveSessionCustomerEventSubscriber();
        });
    }
}
