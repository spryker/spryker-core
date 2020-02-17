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
class DeleteController extends AbstractController
{
    public const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS = 'Relation successfully deleted.';
    protected const MESSAGE_FAILURE = 'Failed to delete relation.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $deleted = $this->getFactory()
            ->getProductRelationFacade()
            ->deleteProductRelation($idProductRelation);

        $this->addMessage($deleted);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param bool $deleted
     *
     * @return void
     */
    protected function addMessage(bool $deleted): void
    {
        if ($deleted) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return;
        }

        $this->addSuccessMessage(static::MESSAGE_FAILURE);
    }
}
