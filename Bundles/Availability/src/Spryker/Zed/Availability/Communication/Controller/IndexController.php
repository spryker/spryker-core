<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Controller;

use Generated\Shared\Transfer\AvailabilityStockTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstract;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Availability\Business\AvailabilityFacade;
use Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory;
use Spryker\Zed\Availability\Communication\Table\AvailabilityAbstractTable;
use Spryker\Zed\Availability\Communication\Table\AvailabilityTable;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AvailabilityFacade getFacade()
 * @method AvailabilityCommunicationFactory getFactory()
 * @method AvailabilityQueryContainerInterface getQueryContainer()
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
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->query->getInt(AvailabilityAbstractTable::URL_PARAM_ID_PRODUCT_ABSTRACT));
        $availabilityTable = $this->getAvailabilityTable($idProductAbstract);
        $localeTransfer =  $this->getCurrentLocaleTransfer();

        $productAbstractEntity = $this->getQueryContainer()
            ->queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $localeTransfer->getIdLocale())
            ->findOne();

        return [
            'productAbstractInfo' => $this->getOverviewData($productAbstractEntity),
            'indexTable' => $availabilityTable->render()
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idProduct = $this->castId($request->query->getInt(AvailabilityTable::URL_PARAM_ID_PRODUCT));
        $idProductAbstract = $this->castId($request->query->getInt(AvailabilityTable::URL_PARAM_ID_PRODUCT_ABSTRACT));
        $sku = $request->query->get(AvailabilityTable::URL_PARAM_SKU);

        $availabilityStockFormDataProvider = $this->createAvailabilityStockFormProvider($idProduct, $sku);

        $availabilityStockForm = $this->getFactory()->createAvailabilityStockForm($availabilityStockFormDataProvider);
        $availabilityStockForm->handleRequest($request);

        if($availabilityStockForm->isValid()) {
            $data = $availabilityStockForm->getData();
            $this->saveData($data);
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
     * @param Request $request
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
     * @return \Spryker\Zed\Availability\Communication\Table\AvailabilityAbstractTable
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
     * @return \Spryker\Zed\Availability\Communication\Table\AvailabilityTable
     */
    protected function getAvailabilityTable($idProductAbstract)
    {
        $localeTransfer = $this->getCurrentLocaleTransfer();

        return $this->getFactory()->createAvailabilityTable($idProductAbstract, $localeTransfer->getIdLocale());
    }

    /**
     * @param $productAbstractEntity
     *
     * @return array
     */
    protected function getOverviewData(SpyProductAbstract $productAbstractEntity)
    {
        return [
            'sku' => $productAbstractEntity->getSku(),
            'productName' => $productAbstractEntity->getProductName(),
            'availability' => $productAbstractEntity->getAvailabilityQuantity(),
            'stockQuantity' => $productAbstractEntity->getStockQuantity(),
            'reservationQuantity' => $this->calculateReservation($productAbstractEntity->getReservationQuantity())
        ];
    }

    /**
     * @param AvailabilityStockTransfer $availabilityStockTransfer
     * @param SpyStock $type
     *
     * @return bool
     */
    protected function stockTypeExist($availabilityStockTransfer, $type)
    {
        foreach ($availabilityStockTransfer->getStocks() as $stockProduct) {
            if ($stockProduct->getStockType() === $type->getName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AvailabilityStockTransfer $availabilityStockTransfer
     * @param SpyStockProduct $stockProductEntity
     * @param string $stockType
     *
     * @return StockProductTransfer
     */
    protected function addStockProduct($availabilityStockTransfer, $stockProductEntity = null, $stockType = null)
    {
        $stockProductTransfer = new StockProductTransfer();

        if ($stockProductEntity !== null) {
            $stockProductTransfer->fromArray($stockProductEntity->toArray(), true);
        } else {
            $stockProductTransfer->setStockType($stockType);
            $stockProductTransfer->setQuantity(0);
        }

        $availabilityStockTransfer->addStockProduct($stockProductTransfer);

        return $stockProductTransfer;
    }

    /**
     * @param string $sku
     * @param SpyStockProduct[]|ObjectCollection $stockProducts
     *
     * @return AvailabilityStockTransfer
     */
    protected function loadAvailabilityStockTransfer($sku, ObjectCollection $stockProducts)
    {
        $availabilityStockTransfer = new AvailabilityStockTransfer();
        $availabilityStockTransfer->setSku($sku);

        foreach ($stockProducts as $stockProduct) {
            $this->addStockProduct($availabilityStockTransfer, $stockProduct);
        }

        return $availabilityStockTransfer;
    }

    /**
     * @param AvailabilityStockTransfer $availabilityStockTransfer
     *
     * @return void
     */
    protected function addEmptyStockType($availabilityStockTransfer)
    {
        $allStockType = $this->getQueryContainer()->queryAllStockType()->find();

        foreach ($allStockType as $type) {
            if (!$this->stockTypeExist($availabilityStockTransfer, $type)) {
                $this->addStockProduct($availabilityStockTransfer, null, $type->getName());
            }
        }
    }

    /**
     * @param AvailabilityStockTransfer $data
     *
     * @return void
     */
    protected function saveData(AvailabilityStockTransfer $data)
    {
        foreach ($data->getStocks() as $stockProductTransfer) {
            if ($stockProductTransfer->getIdStockProduct() !== null) {
                $this->getFactory()->getStockFacade()->updateStockProduct($stockProductTransfer);
            }

            if ($stockProductTransfer->getIdStockProduct() === null && (int)$stockProductTransfer->getQuantity() !== 0) {
                $stockProductTransfer->setSku($data->getSku());
                $this->getFactory()->getStockFacade()->createStockProduct($stockProductTransfer);
            }
        }
    }

    /**
     * @param int $idProduct
     * @param string $sku
     *
     * @return \Spryker\Zed\Availability\Communication\Form\DataProvider\AvailabilityStockFormDataProvider
     */
    protected function createAvailabilityStockFormProvider($idProduct, $sku)
    {
        $stockProducts = $this->getQueryContainer()->queryStockByIdProduct($idProduct)->find();
        $availabilityStockTransfer = $this->loadAvailabilityStockTransfer($sku, $stockProducts);
        $this->addEmptyStockType($availabilityStockTransfer);

        $availabilityStockFormDataProvider = $this->getFactory()->createAvailabilityStockFormDataProvider($availabilityStockTransfer);

        return $availabilityStockFormDataProvider;
    }

    /**
     * @param string $reservationQuantity
     *
     * @return int
     */
    protected function calculateReservation($reservationQuantity)
    {
        $reservationItems = explode(',', $reservationQuantity);
        $reservationItems = array_unique($reservationItems);

        return $this->getReservationUniqueValue($reservationItems);
    }

    /**
     * @param array $reservationItems
     *
     * @return int
     */
    protected function getReservationUniqueValue($reservationItems)
    {
        $reservation = 0;
        foreach ($reservationItems as $item) {
            $value = explode(':', $item);

            if(count($value) > 1 ) {
                $reservation += $value[1];
            }
        }

        return $reservation;
    }
}
