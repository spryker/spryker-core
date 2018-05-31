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
class BuildController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $spryk = $request->query->get('spryk');

        $sprykForm = $this->getFactory()
            ->getSprykForm($spryk)
            ->handleRequest($request);

        if ($sprykForm->isSubmitted() && $sprykForm->isValid()) {
            if ($sprykForm->get('create')->isClicked()) {
                return $this->viewResponse(
                    $this->getFacade()->buildSprykView($spryk, $sprykForm->getData())
                );
            }

            $runResult = $this->getFacade()->runSpryk($spryk, $sprykForm->getData());
            if ($runResult) {
                $this->addSuccessMessage(sprintf('Spryk "%s" executed successfully.', $spryk));
                $messages = explode("\n", rtrim($runResult, "\n"));
                if (count($messages) > 1) {
                    $this->addInfoMessage(nl2br($runResult));
//                    foreach ($messages as $message) {
//                    }
                }
            }
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
        ]);
    }
}
