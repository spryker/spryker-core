<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $sprykForm = $this->getFactory()
            ->getSprykSelectForm()
            ->handleRequest($request);

        if ($sprykForm->isSubmitted() && $sprykForm->isValid()) {
            $data = $sprykForm->getData();
            $sprykNameToBeBuild = $data['spryk'];

            return $this->redirectResponse('/spryk-gui/index/build?spryk=' . $sprykNameToBeBuild);
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function buildAction(Request $request): array
    {
        $spryk = $request->query->get('spryk');

        $sprykForm = $this->getFactory()
            ->getSprykForm($spryk)
            ->handleRequest($request);

        if ($sprykForm->isSubmitted() && $sprykForm->isValid()) {
            return $this->viewResponse(
                $this->getFacade()->buildSprykView($sprykForm->getData())
            );
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
        ]);
    }
}
