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
 */
class ViewController extends AbstractController
{
    public const PARAM_ID = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductSet = $this->castId($request->query->get(static::PARAM_ID));

        $productSetTransfer = new ProductSetTransfer();
        $productSetTransfer->setIdProductSet($idProductSet);

        $productSetTransfer = $this->getFactory()
            ->getProductSetFacade()
            ->findProductSet($productSetTransfer);

        if (!$productSetTransfer) {
            $this->addErrorMessage(sprintf(
                'Product Set #%d not found.',
                $idProductSet
            ));

            return $this->redirectResponse(Url::generate('/product-set-gui')->build());
        }

        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();

        return $this->viewResponse([
            'productSetTransfer' => $productSetTransfer,
            'localeCollection' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'productAbstractSetViewTable' => $this->getFactory()->createProductAbstractSetViewTable($localeTransfer, $idProductSet)->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractSetViewTableAction(Request $request)
    {
        $idProductSet = $this->castId($request->query->get(static::PARAM_ID));

        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        return $this->jsonResponse(
            $this->getFactory()
                ->createProductAbstractSetViewTable($localeTransfer, $idProductSet)
                ->fetchData()
        );
    }
}
