<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use FilesystemIterator;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormRendererEngineInterface;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class FormRuntimeLoaderTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

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
        $twig->addRuntimeLoader($this->createFactoryRuntimeLoader($twig, $container));

        return $twig;
    }

    /**
     * @return array
     */
    protected function getTwigTemplateFileNames(): array
    {
        $files = new FilesystemIterator(
            $this->getConfig()->getFormResourcesPath(),
            FilesystemIterator::SKIP_DOTS | FilesystemIterator::KEY_AS_PATHNAME
        );

        $typeTemplates = $this->getConfig()->getDefaultTemplateFileNames();
        foreach ($files as $file) {
            $typeTemplates[] = $file->getFilename();
        }

        return $typeTemplates;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Symfony\Component\Form\FormRendererEngineInterface
     */
    protected function createTwigRendererEngine(Environment $twig): FormRendererEngineInterface
    {
        return new TwigRendererEngine($this->getTwigTemplateFileNames(), $twig);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface|null $csrfTokenManager
     *
     * @return \Symfony\Component\Form\FormRendererInterface
     */
    protected function createFormRenderer(Environment $twig, ?CsrfTokenManagerInterface $csrfTokenManager = null): FormRendererInterface
    {
        return new FormRenderer($this->createTwigRendererEngine($twig), $csrfTokenManager);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\RuntimeLoader\FactoryRuntimeLoader
     */
    protected function createFactoryRuntimeLoader(Environment $twig, ContainerInterface $container)
    {
        $formRendererCallback = function () use ($twig, $container) {
            if ($container->has(static::SERVICE_FORM_CSRF_PROVIDER)) {
                return $this->createFormRenderer($twig, $container->get(static::SERVICE_FORM_CSRF_PROVIDER));
            }

            return $this->createFormRenderer($twig);
        };

        $loadersMap = [];
        $loadersMap[FormRenderer::class] = $formRendererCallback;
        if (class_exists(TwigRenderer::class)) {
            $loadersMap[TwigRenderer::class] = $formRendererCallback;
        }

        return new FactoryRuntimeLoader($loadersMap);
    }
}
