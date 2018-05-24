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
     * @return array
     */
    public function indexAction(): array
    {
        $sprykDefinitions = $this->getFacade()->getSprykDefinitions();

        return $this->viewResponse([
            'sprykDefinitions' => $sprykDefinitions,
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
            if ($sprykForm->get('create')->isClicked()) {
                return $this->viewResponse(
                    $this->getFacade()->buildSprykView($spryk, $sprykForm->getData())
                );
            }

            $runResult = $this->getFacade()->runSpryk($spryk, $sprykForm->getData());
            if ($runResult) {
                $this->addSuccessMessage(sprintf('Spryk "%s" executed successfully.', $spryk));
            }
            if (!$runResult) {
                $this->addErrorMessage(sprintf('Spryk "%s" not executed successfully.', $spryk));
            }
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
        ]);
    }
}
