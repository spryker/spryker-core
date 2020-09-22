<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Plugin\Twig;

use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\GuiTable\Communication\GuiTableCommunicationFactory getFactory()
 */
class GuiTableConfigurationTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            $this->createFunction(),
        ];
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function createFunction(): TwigFunction
    {
        $functionProvider = $this->getFactory()->createGuiTableConfigurationFunctionProvider();

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions()
        );
    }
}
