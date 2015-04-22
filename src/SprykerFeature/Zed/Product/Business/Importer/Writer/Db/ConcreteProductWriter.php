<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Db;

use SprykerFeature\Zed\Product\Business\Importer\Model\ConcreteProduct;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ConcreteProductWriterInterface;

/**
 * Class ConcreteProductWriter
 *
 * @package SprykerFeature\Zed\Product\Business\Importer\Writer\Db
 */
class ConcreteProductWriter implements ConcreteProductWriterInterface
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
        $this->createProductStatement();
        $this->createAttributesStatement();
    }


    /**
     * @param ConcreteProduct $product
     *
     * @return bool success
     */
    public function writeProduct(ConcreteProduct $product)
    {
        return (
            $this->productStatement->execute(
                [
                    ':sku' => $product->getSku(),
                    ':isActive' => (int) $product->isActive(),
                    ':abstractProductSku' => $product->getAbstractProductSku()
                ]
            ) &&
            $this->attributesStatement->execute(
                [
                    ':productSku' => $product->getSku(),
                    ':name' => $product->getName(),
                    ':url' => (string) $product->getUrl(),
                    ':attributes' => json_encode($product->getAttributes()),
                    ':locale' => $this->defaultLocale
                ]
            )
        );
    }

    /**
     * create the product insert statement
     */
    protected function createProductStatement()
    {
        $connection = \Propel\Runtime\Propel::getConnection();
        //The subselect is necessary, cause MySQL does not have the ability to use a join inside an insert
        $this->productStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s
                  (%2$s, %3$s, %4$s) VALUES
                  (:sku, :isActive, (SELECT %5$s FROM %6$s WHERE %7$s = :abstractProductSku))
                  ON DUPLICATE KEY UPDATE
                      %2$s=VALUES(%2$s),
                      %3$s=VALUES(%3$s),
                      %4$s=VALUES(%4$s);',
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_SKU,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_IS_ACTIVE,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_ABSTRACT_PRODUCT_ID,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_ABSTRACT_PRODUCT_ID,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap::COL_SKU
            )
        );
    }

    /**
     * Creates the attribute statement
     */
    protected function createAttributesStatement()
    {
        $connection = \Propel\Runtime\Propel::getConnection();
        //The subselect is necessary, cause MySQL does not have the ability to use a join inside an insert
        $this->attributesStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s, %3$s, %4$s, %5$s, %6$s) VALUES(
                    (SELECT %7$s FROM %8$s WHERE %9$s = :productSku),
                    :locale,
                    :name,
                    :url,
                    :attributes
                ) ON DUPLICATE KEY UPDATE
                    %2$s=VALUES(%2$s),
                    %3$s=VALUES(%3$s),
                    %4$s=VALUES(%4$s),
                    %5$s=VALUES(%5$s),
                    %6$s=VALUES(%6$s);',
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap::COL_ID_PRODUCT,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap::COL_LOCALE,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap::COL_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap::COL_URL,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_ID_PRODUCT,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_TABLE_NAME,
                \SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap::COL_SKU
            )
        );
    }
}
