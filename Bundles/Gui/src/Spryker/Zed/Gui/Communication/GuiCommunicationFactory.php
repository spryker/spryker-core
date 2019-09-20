<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Zed\Gui\Communication\Form\Type\Extension\NoValidateTypeExtension;
use Spryker\Zed\Gui\GuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormTypeExtensionInterface;

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
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createNoValidateFormTypeExtension(): FormTypeExtensionInterface
    {
        return new NoValidateTypeExtension();
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getTwigFilters(): array
    {
        return $this->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FILTERS);
    }
}
