<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonGroupUrlGenerator;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonUrlGenerator;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\UrlGeneratorInterface;
use Spryker\Zed\Gui\GuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class GuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createFilesystemLoader(): FilesystemLoaderInterface
    {
        return new FilesystemLoader($this->getConfig()->getTemplatePaths());
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getTwigFilters(): array
    {
        return $this->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FILTERS);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return \Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\UrlGeneratorInterface
     */
    public function createButtonUrlGenerator(string $url, string $title, array $options): UrlGeneratorInterface
    {
        return new ButtonUrlGenerator($url, $title, $options);
    }

    /**
     * @param array $buttons
     * @param string $title
     * @param array $options
     *
     * @return \Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\UrlGeneratorInterface
     */
    public function createButtonGroupUrlGenerator(array $buttons, string $title, array $options): UrlGeneratorInterface
    {
        return new ButtonGroupUrlGenerator($buttons, $title, $options);
    }
}
