<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 */
class ApplicationTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
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
        $environment = $this->getFactory()->getEnvironment()->getEnvironment();

        $vars = [
            'environment' => $environment,
            'title' => $this->buildApplicationTitle($environment),
        ];

        foreach ($vars as $name => $value) {
            $twig->addGlobal($name, $value);
        }

        return $twig;
    }

    /**
     * @param string $environment
     *
     * @return string
     */
    protected function buildApplicationTitle(string $environment): string
    {
        $projectNamespace = $this->getConfig()->getProjectNamespace();

        $applicationTitle = sprintf('%s | Zed | %s', $projectNamespace, ucfirst($environment));

        return $applicationTitle;
    }
}
