<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\Business\CmsGuiFacade getFacade()
 */
class CreatePageController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $pageTabs = $this->getFactory()->createPageTabs();

        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        return [
            'pageTabs' => $pageTabs->createView(),
            'availableLocales' => $availableLocales,
        ];
    }
}
