<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerFeature\Zed\Price\Persistence\Propel\Base\SpyPriceProduct;

class BulkWriter extends Writer implements BulkWriterInterface
{
    /**
     * @var array
     */
    protected $recordsToTouch = [];

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @throws \Exception
     *
     * @return SpyPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $transferPriceProduct = $this->setPriceType($transferPriceProduct);

        $this->loadProductIdsForPriceProductTransfer($transferPriceProduct);

        $entity = $this->locator->price()->entitySpyPriceProduct();
        $newPrice = $this->savePriceProductEntity($transferPriceProduct, $entity);

        $this->addRecordToTouch(self::TOUCH_PRODUCT, $transferPriceProduct->getIdAbstractProduct());

        return $newPrice;
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @throws \Exception
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $transferPriceProduct = $this->setPriceType($transferPriceProduct);

        $this->loadProductIdsForPriceProductTransfer($transferPriceProduct);

        $priceProductEntity = $this->getPriceProductById($transferPriceProduct->getIdPriceProduct());
        $this->savePriceProductEntity($transferPriceProduct, $priceProductEntity);

        $this->addRecordToTouch(self::TOUCH_PRODUCT, $transferPriceProduct->getIdAbstractProduct());
    }

    /**
     * @param string $itemType
     * @param int $itemId
     */
    protected function addRecordToTouch($itemType, $itemId)
    {
        $this->recordsToTouch[$itemType][] = $itemId;
    }

    public function flush()
    {
        foreach ($this->recordsToTouch as $itemType => $itemIds) {
            $this->touchFacade->bulkTouchActive($itemType, $itemIds);
            unset($this->recordsToTouch[$itemType]);
        }
    }
}
