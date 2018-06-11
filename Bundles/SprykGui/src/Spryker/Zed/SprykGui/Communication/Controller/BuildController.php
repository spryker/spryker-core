<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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

        $canRunBuild = $this->canRunBuild($sprykForm);
        if ($sprykForm->isSubmitted() && $canRunBuild && $sprykForm->isValid()) {
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
            'messages' => (isset($messages)) ? $messages : [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sprykForm
     *
     * @return bool
     */
    protected function canRunBuild(FormInterface $sprykForm): bool
    {
        if ($sprykForm->has('run') && $sprykForm->get('run')->isClicked()) {
            return true;
        }
        if ($sprykForm->has('create') && $sprykForm->get('create')->isClicked()) {
            return true;
        }

        return false;
    }
}
