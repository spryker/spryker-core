<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

use Generated\Shared\Transfer\ModuleTransfer;
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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $spryk = $request->query->get('spryk');

        $sprykForm = $this->getFactory()
            ->getSprykMainForm($spryk)
            ->handleRequest($request);

        $messages = [];
        if ($sprykForm->isSubmitted() && $sprykForm->isValid()) {
            $moduleTransfer = $sprykForm->get('module')->getData();

            return $this->redirectResponse(sprintf('/spryk-gui/build/spryk-details?spryk=%s&module=%s&moduleOrganization=%s', $spryk, $moduleTransfer->getName(), $moduleTransfer->getOrganization()));
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
            'messages' => $messages,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function sprykDetailsAction(Request $request)
    {
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer->setName($request->query->get('module'))
            ->setOrganization($request->query->get('moduleOrganization'));

        $sprykForm = $this->getFactory()
            ->getSprykDetailsForm($moduleTransfer, $request->query->get('spryk'))
            ->handleRequest($request);

        $messages = [];
        if ($sprykForm->isSubmitted() && $sprykForm->isValid()) {
            $formData = $sprykForm->getData();

            if ($sprykForm->get('create')->isClicked()) {
                return $this->viewResponse(
                    $this->getFacade()->buildSprykView($request->query->get('spryk'), $formData)
                );
            }

            $runResult = $this->getFacade()->runSpryk($request->query->get('spryk'), $formData);
            if ($runResult) {
                $this->addSuccessMessage(sprintf('Spryk "%s" executed successfully.', $request->query->get('spryk')));
                $messages = explode("\n", rtrim($runResult, "\n"));
            }
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
            'messages' => $messages,
        ]);
    }
}
