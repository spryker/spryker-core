<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 */
class UserTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const TWIG_GLOBAL_VARIABLE_USERNAME = 'username';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigGlobalVariables($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigGlobalVariables(Environment $twig): Environment
    {
        $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_USERNAME, $this->getUsername());

        return $twig;
    }

    /**
     * @return string
     */
    protected function getUsername(): string
    {
        $username = '';

        if ($this->getFacade()->hasCurrentUser()) {
            $user = $this->getFacade()->getCurrentUser();
            $username = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
        }

        return $username;
    }
}
