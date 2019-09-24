<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action;

/**
 * @deprecated Use `Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\EditActionButtonTwigPlugin` instead.
 */
class EditActionButtonFunction extends AbstractActionButtonFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-edit';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-pencil-square-o"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'editActionButton';
    }
}
