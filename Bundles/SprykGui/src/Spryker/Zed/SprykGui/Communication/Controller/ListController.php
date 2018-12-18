<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiQueryContainerInterface getQueryContainer()
 */
class ListController extends AbstractController
{
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    public function indexAction(): array
    {
        if (!$this->isSprykAvailable()) {
            throw new NotFoundHttpException(static::MESSAGE_SPRYK_ERROR);
        }

        $sprykDefinitions = $this->getFacade()->getSprykDefinitions();

        return $this->viewResponse([
            'sprykDefinitions' => $sprykDefinitions,
        ]);
    }
}
