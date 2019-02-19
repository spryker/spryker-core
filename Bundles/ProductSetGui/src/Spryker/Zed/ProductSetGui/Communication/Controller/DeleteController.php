<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Controller;

use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class DeleteController extends AbstractController
{
    public const PARAM_ID = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductSet = $this->castId($request->query->get(static::PARAM_ID));

        $productSetTransfer = new ProductSetTransfer();
        $productSetTransfer->setIdProductSet($idProductSet);

        $this->getFactory()
            ->getProductSetFacade()
            ->deleteProductSet($productSetTransfer);

        $this->addSuccessMessage('Product Set #%d deleted successfully.', [
            '%d' => $productSetTransfer->getIdProductSet(),
        ]);

        return $this->redirectResponse(
            Url::generate('/product-set-gui')->build()
        );
    }
}
