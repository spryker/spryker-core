<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action;

/**
 * @deprecated Use `\Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\BackActionButtonTwigPlugin` instead.
 */
class BackActionButtonFunction extends AbstractActionButtonFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-back';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-angle-double-left"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'backActionButton';
    }
}
