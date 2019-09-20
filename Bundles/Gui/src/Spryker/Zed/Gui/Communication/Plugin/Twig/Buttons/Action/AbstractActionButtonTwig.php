<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action;

use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\AbstractButtonTwig;

abstract class AbstractActionButtonTwig extends AbstractButtonTwig
{
    public const DEFAULT_CSS_CLASSES = 'btn-sm btn-outline safe-submit';
}
