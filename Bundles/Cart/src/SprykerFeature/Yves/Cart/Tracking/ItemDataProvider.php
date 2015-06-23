<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Tracking;

use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Yves\Factory;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Yves\Library\Tracking\DataProvider\AbstractDataProvider;

/**
 * Class ItemDataProvider
 * @package SprykerFeature\Yves\Cart\Model\Tracking
 */
class ItemDataProvider extends AbstractDataProvider implements \Iterator
{

    const DATA_PROVIDER_NAME = 'item tracking data provider';

    /** @var CartDataProvider */
    protected $cartDataProvider;

    /** @var \ArrayIterator */
    protected $items;

    /** @var int */
    protected $offset = 0;

    /** @var array */
    protected $products = [];

    /** @var array */
    protected $currentProduct;

    /**
     * @param CartDataProvider $cartDataProvider
     */
    public function __construct(CartDataProvider $cartDataProvider)
    {
        $this->cartDataProvider = $cartDataProvider;
    }

    /**
     * @param string $sku
     * @return array
     */
    public function getProductBySku($sku)
    {
        if (!array_key_exists($sku, $this->products)) {
            $storageKeyValue = $this->cartDataProvider->app->getStorageKeyValue();
            $product = Factory::getInstance()->createCatalogDependencyContainer()
                ->createCatalogModel($storageKeyValue)
                ->getProductDataBySku($sku);
            $this->products[$sku] = $product;
        }

        return $this->products[$sku];
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     *
     */
    public function initItems()
    {
    }

    /**
     * @return float
     */
    public function getUnitGrossPrice()
    {
        $currentItem = $this->getCurrentItem();
        $unitGrossPrice = $currentItem->getUnitGrossPrice();

        return $unitGrossPrice;
    }

    public function getUnitGrossPriceFormatted()
    {
        return CurrencyManager::getInstance()->convertCentToDecimal($this->getUnitGrossPrice());
    }

    public function getUnitNetPrice()
    {
        $currentItem = $this->getCurrentItem();
        $unitGrossPrice = $currentItem->getUnitGrossPrice();
        $taxPercentage = $currentItem->getTaxPercentage();
        $unitNetPrice = $unitGrossPrice / (1 + ($taxPercentage / 100));

        return $unitNetPrice;
    }

    /**
     * @return float
     */
    public function getUnitNetPriceFormatted()
    {
        return CurrencyManager::getInstance()->convertCentToDecimal($this->getUnitNetPrice());
    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        $currentItem = $this->getCurrentItem();
        $discounts = $currentItem->getDiscounts();
        $amount = 0;
        foreach ($discounts as $discount) {
            $amount += $discount->getAmount();
        }

        return $amount;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $currentItem = $this->getCurrentItem();

        return str_replace('"', '\'', $currentItem->getName());
    }

    /**
     * @return string
     */
    public function getSku()
    {
        $currentItem = $this->getCurrentItem();

        return $currentItem->getSku();
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        $currentItem = $this->getCurrentItem();

        return $currentItem->getQuantity();
    }

    /**
     * @return OrderItem
     */
    public function getCurrentItem()
    {
        return $this->items[$this->offset];
    }

    /**
     * @see http://php.net/manual/en/iterator.current.php
     * @return OrderItem
     */
    public function current()
    {
        if (!$this->items) {
            $this->initItems();
        }

        return $this;
    }

    /**
     * @see http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        $this->offset++;
    }

    /**
     * @see http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if (!$this->items) {
            $this->initItems();
        }

        return key($this->items[$this->offset]);
    }

    /**
     * @see http://php.net/manual/en/iterator.valid.php
     * @return bool
     */
    public function valid()
    {
        if (!$this->items) {
            $this->initItems();
        }

        return isset($this->items[$this->offset]);
    }

    /**
     * @see http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * @return array
     */
    protected function findCurrentProduct()
    {
        if (!$this->currentProduct) {
            $sku = $this->getSku();
            $this->currentProduct = $this->getProductBySku($sku);
        }

        return $this->currentProduct;
    }

}
