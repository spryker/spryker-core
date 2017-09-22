<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Plugin;

use Spryker\Yves\Kernel\Controller\View;
use Symfony\Component\HttpFoundation\Request;

interface WidgetBuilderPluginInterface
{

    /**
     * TODO: add specification
     *
     * @api
     *
     * @param \Spryker\Yves\Kernel\Controller\View $view
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\Controller\Widget
     */
    public function buildWidget(View $view, Request $request);

}
