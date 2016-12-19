<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Controller;

use Generated\Shared\Transfer\AvailabilityStockTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable;
use Spryker\Zed\AvailabilityGui\Communication\Table\BundledProductAvailabilityTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiFacade getFacade()
 * @method \Spryker\Zed\AvailabilityGui\Communication\AvailabilityGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $availabilityAbstractTable = $this->getAvailabilityAbstractTable();

        return [
            'indexTable' => $availabilityAbstractTable->render()
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->query->getInt(AvailabilityAbstractTable::URL_PARAM_ID_PRODUCT_ABSTRACT));
        $availabilityTable = $this->getAvailabilityTable($idProductAbstract);
        $localeTransfer = $this->getCurrentLocaleTransfer();

        $productAbstractAvailabilityTransfer = $this->getFactory()
            ->getAvailabilityFacade()
            ->getProductAbstractAvailability(
                $idProductAbstract,
                $localeTransfer->getIdLocale()
            );

        $bundledProductAvailabilityTable = $this->getBundledProductAvailabilityTable();

        return [
            'productAbstractAvailability' => $productAbstractAvailabilityTransfer,
            'indexTable' => $availabilityTable->render(),
            'bundledProductAvailabilityTable' => $bundledProductAvailabilityTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idProduct = $this->castId($request->query->getInt(AvailabilityTable::URL_PARAM_ID_PRODUCT));
        $idProductAbstract = $this->castId($request->query->getInt(AvailabilityTable::URL_PARAM_ID_PRODUCT_ABSTRACT));
        $sku = $request->query->get(AvailabilityTable::URL_PARAM_SKU);

        $availabilityStockForm = $this->getFactory()->createAvailabilityStockForm($idProduct, $sku);
        $availabilityStockForm->handleRequest($request);

        if ($availabilityStockForm->isValid()) {
            $data = $availabilityStockForm->getData();
            if ($this->saveAvailabilityStock($data)) {
                $this->addSuccessMessage('Stock successfully updated');
            } else {
                $this->addErrorMessage('Failed to update stock');
            }
        }

        return [
            'form' => $availabilityStockForm->createView(),
            'idProductAbstract' => $idProductAbstract,
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availabilityAbstractTableAction()
    {
        $availabilityAbstractTable = $this->getAvailabilityAbstractTable();

        return $this->jsonResponse(
            $availabilityAbstractTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availabilityTableAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->query->getInt(AvailabilityAbstractTable::URL_PARAM_ID_PRODUCT_ABSTRACT));
        $availabilityTable = $this->getAvailabilityTable($idProductAbstract);

        return $this->jsonResponse(
            $availabilityTable->fetchData()
        );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bundledProductAvailabilityTableAction(Request $request)
    {
        $idBundleProduct = $request->query->getInt(BundledProductAvailabilityTable::URL_PARAM_ID_PRODUCT_BUNDLE);

        if (!$idBundleProduct) {
            return $this->jsonResponse([]);
        }

        $idBundleProduct = $this->castId($idBundleProduct);
        $bundledProductAvailabilityTable = $this->getBundledProductAvailabilityTable($idBundleProduct);

        return $this->jsonResponse(
            $bundledProductAvailabilityTable->fetchData()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable
     */
    protected function getAvailabilityAbstractTable()
    {
        $localeTransfer = $this->getCurrentLocaleTransfer();

        return $this->getFactory()->createAvailabilityAbstractTable($localeTransfer->getIdLocale());
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocaleTransfer()
    {
        $localeFacade = $this->getFactory()->getLocalFacade();

        return $localeFacade->getCurrentLocale();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable
     */
    protected function getAvailabilityTable($idProductAbstract)
    {
        $localeTransfer = $this->getCurrentLocaleTransfer();

        return $this->getFactory()->createAvailabilityTable($idProductAbstract, $localeTransfer->getIdLocale());
    }

    /**
     * @param int $idProductBundle
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\BundledProductAvailabilityTable
     */
    protected function getBundledProductAvailabilityTable($idProductBundle = null)
    {
        $localeTransfer = $this->getCurrentLocaleTransfer();

        return $this->getFactory()
            ->createBundledProductAvailabilityTable(
                $localeTransfer->getIdLocale(),
                $idProductBundle
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     *
     * @return bool
     */
    protected function saveAvailabilityStock(AvailabilityStockTransfer $availabilityStockTransfer)
    {
        $isAnyItemsUpdated = false;
        foreach ($availabilityStockTransfer->getStocks() as $stockProductTransfer) {
            if ($stockProductTransfer->getIdStockProduct() !== null) {
                if ($this->getFactory()->getStockFacade()->updateStockProduct($stockProductTransfer) > 0) {
                    $isAnyItemsUpdated = true;
                }
            }

            if ($stockProductTransfer->getIdStockProduct() === null && (int)$stockProductTransfer->getQuantity() !== 0) {
                $stockProductTransfer->setSku($availabilityStockTransfer->getSku());
                if ($this->getFactory()->getStockFacade()->createStockProduct($stockProductTransfer) > 0) {
                    $isAnyItemsUpdated = true;
                }
            }
        }

        return $isAnyItemsUpdated;
    }

}
