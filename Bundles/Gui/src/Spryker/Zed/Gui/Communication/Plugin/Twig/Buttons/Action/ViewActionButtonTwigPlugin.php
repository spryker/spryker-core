<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class ViewActionButtonTwigPlugin extends AbstractActionButtonTwig
{
    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return 'viewActionButton';
    }

    /**
     * @return string
     */
    protected function getButtonClass(): string
    {
        return 'btn-view';
    }

    /**
     * @return string
     */
    protected function getIcon(): string
    {
        return '<i class="fa fa-caret-right"></i> ';
    }
}
