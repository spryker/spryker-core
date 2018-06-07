<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dashboard\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Dashboard\Communication\DashboardCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $plugins = $this->getFactory()->getDateFormatterService();

        $pluginContents = [];
        foreach ($plugins as $plugin) {
            $pluginContents[] = $plugin->render();
        }

        return [
            'pluginContents' => $pluginContents,
        ];
    }
}
