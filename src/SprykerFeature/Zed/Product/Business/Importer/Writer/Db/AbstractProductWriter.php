<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Db;

use SprykerFeature\Zed\Product\Business\Importer\Writer\AbstractProductWriterInterface;
use SprykerFeature\Zed\Product\Business\Importer\Model\AbstractProduct;

/**
 * Class AbstractProductWriter
 *
 * @package SprykerFeature\Zed\Product\Business\Importer\Writer\Db
 */
class AbstractProductWriter implements AbstractProductWriterInterface
{
    /**
     * @var \PDOStatement
     */
    protected $productStatement;

    /**
     * @var \PDOStatement
     */
    protected $attributesStatement;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
        $connection = \Propel\Runtime\Propel::getConnection();
        $this->productStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s) VALUES (:sku)
                ON DUPLICATE KEY UPDATE %2$s=VALUES(%2$s);',
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_SKU
            )
        );
        $this->attributesStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s, %3$s, %4$s, %5$s) VALUES(
                    (SELECT %6$s FROM %7$s WHERE %8$s = :abstractProductSku),
                    :locale,
                    :name,
                    :attributes
                ) ON DUPLICATE KEY UPDATE
                    %2$s=VALUES(%2$s),
                    %3$s=VALUES(%3$s),
                    %4$s=VALUES(%4$s),
                    %5$s=VALUES(%5$s);',
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap::COL_ABSTRACT_PRODUCT_ID,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap::COL_LOCALE,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_ABSTRACT_PRODUCT_ID,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_SKU
            )
        );
    }

    /**
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function writeAbstractProduct(AbstractProduct $product)
    {
         return (
            $this->productStatement->execute([':sku' => $product->getSku()]) &&
            $this->attributesStatement->execute(
                [
                    ':attributes' => json_encode($product->getAttributes()),
                    ':name' => $product->getName(),
                    ':abstractProductSku' => $product->getSku(),
                    ':locale' => $this->defaultLocale
                ]
            )
         );
    }
}
