<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductRelationGui\Communication\ProductRelationGuiCommunicationFactory getFactory()
 */
class ToggleActiveController extends AbstractController
{
    public const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_ACTIVATE_SUCCESS = 'Relation successfully activated.';
    protected const MESSAGE_DEACTIVATE_SUCCESS = 'Relation successfully deactivated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFactory()
            ->getProductRelationFacade()
            ->activateProductRelation($idProductRelation);

        $this->addSuccessMessage(static::MESSAGE_ACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFactory()
            ->getProductRelationFacade()
            ->deactivateProductRelation($idProductRelation);

        $this->addSuccessMessage(static::MESSAGE_DEACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
