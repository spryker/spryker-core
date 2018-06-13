<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see \Spryker\Zed\ProductListGui\Communication\Controller\IndexController::indexAction()
     */
    protected const URL_LIST = '/product-list-gui';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse twig variables
     */
    public function indexAction(Request $request): array
    {
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_LIST);

        $form = $this->getFactory()
            ->getProductListForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productListTransfer = $form->getData();
//            $productListTransfer = $this->getFactory()
//                ->getCompanyBusinessUnitFacade()
//                ->create($productListTransfer);
//
//            if (!$companyResponseTransfer->getIsSuccessful()) {
//                $this->addErrorMessage(static::MESSAGE_COMPANY_BUSINESS_UNIT_CREATE_ERROR);
//
//                return $this->viewResponse([
//                    'form' => $form->createView(),
//                ]);
//            }
//
//            $this->addSuccessMessage(static::MESSAGE_COMPANY_BUSINESS_UNIT_CREATE_SUCCESS);
//
//            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
