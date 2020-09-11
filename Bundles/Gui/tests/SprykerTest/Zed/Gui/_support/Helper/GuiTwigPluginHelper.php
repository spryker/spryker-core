<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\BackActionButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\CreateActionButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\EditActionButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\RemoveActionButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\ViewActionButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonGroupTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Form\SubmitButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\BackTableButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\CreateTableButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\EditTableButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\RemoveTableButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\ViewTableButtonTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormRuntimeLoaderTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\GuiFilterTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\GuiTwigLoaderPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\TabsTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlDecodeTwigPlugin;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlTwigPlugin;
use SprykerTest\Zed\Twig\Helper\TwigHelperTrait;

class GuiTwigPluginHelper extends Module
{
    use TwigHelperTrait;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getTwigHelper()
            ->addTwigPlugin(new AssetsPathTwigPlugin())
            ->addTwigPlugin(new BackActionButtonTwigPlugin())
            ->addTwigPlugin(new CreateActionButtonTwigPlugin())
            ->addTwigPlugin(new EditActionButtonTwigPlugin())
            ->addTwigPlugin(new RemoveActionButtonTwigPlugin())
            ->addTwigPlugin(new ViewActionButtonTwigPlugin())
            ->addTwigPlugin(new ButtonGroupTwigPlugin())
            ->addTwigPlugin(new SubmitButtonTwigPlugin())
            ->addTwigPlugin(new BackTableButtonTwigPlugin())
            ->addTwigPlugin(new CreateTableButtonTwigPlugin())
            ->addTwigPlugin(new EditTableButtonTwigPlugin())
            ->addTwigPlugin(new RemoveTableButtonTwigPlugin())
            ->addTwigPlugin(new ViewTableButtonTwigPlugin())
            ->addTwigPlugin(new GuiFilterTwigPlugin())
            ->addTwigPlugin(new TabsTwigPlugin())
            ->addTwigPlugin(new UrlDecodeTwigPlugin())
            ->addTwigPlugin(new UrlTwigPlugin())
            ->addTwigPlugin(new FormRuntimeLoaderTwigPlugin());

        $this->getTwigHelper()->addLoaderPlugin(new GuiTwigLoaderPlugin());
    }
}
