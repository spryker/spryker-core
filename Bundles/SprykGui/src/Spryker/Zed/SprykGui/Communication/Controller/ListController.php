<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

/**
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiQueryContainerInterface getQueryContainer()
 */
class ListController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $sprykDefinitions = $this->getFacade()->getSprykDefinitions();

        return $this->viewResponse([
            'sprykDefinitions' => $sprykDefinitions,
        ]);
    }
}
