<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Db;

use Generated\Shared\Locale\LocaleInterface;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ConcreteProductWriterInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;

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
    protected $localeTransfer;

    /**
     * @param LocaleInterface $localeTransfer
     */
    public function __construct(LocaleInterface $localeTransfer)
    {
        $this->localeTransfer = $localeTransfer;
        $this->createProductStatement();
        $this->createAttributesStatement();
    }

    /**
     * @param ConcreteProductTransfer $product
     *
     * @return bool
     */
    public function writeProduct(ConcreteProductTransfer $product)
    {
        $this->productStatement->execute(
            [
                ':sku' => $product->getSku(),
                ':isActive' => (int) $product->getIsActive(),
                ':attributes' => json_encode($product->getAttributes()),
                ':abstractProductSku' => $product->getAbstractProductSku(),
            ]
        );

        foreach ($product->getLocalizedAttributes() as $localizedAttributes) {
            $this->attributesStatement->execute(
                [
                    ':productSku' => $product->getSku(),
                    ':name' => $localizedAttributes->getName(),
                    ':attributes' => json_encode($localizedAttributes->getAttributes()),
                    ':fkLocale' => $this->localeTransfer->getIdLocale(),
                ]
            );
        }

        return true;
    }

    /**
     * create the product insert statement
     */
    protected function createProductStatement()
    {
        $connection = Propel::getConnection();
        //The subselect is necessary, cause MySQL does not have the ability to use a join inside an insert
        $this->productStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s
                  (%2$s, %3$s, %4$s , %5$s) VALUES
                  (:sku, :isActive, (SELECT %6$s FROM %7$s WHERE %8$s = :abstractProductSku), :attributes)
                  ON DUPLICATE KEY UPDATE
                      %2$s=VALUES(%2$s),
                      %3$s=VALUES(%3$s),
                      %4$s=VALUES(%4$s),
                      %5$s=VALUES(%5$s);',
                SpyProductTableMap::TABLE_NAME,
                SpyProductTableMap::COL_SKU,
                SpyProductTableMap::COL_IS_ACTIVE,
                SpyProductTableMap::COL_FK_ABSTRACT_PRODUCT,
                SpyProductTableMap::COL_ATTRIBUTES,
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                SpyAbstractProductTableMap::TABLE_NAME,
                SpyAbstractProductTableMap::COL_SKU
            )
        );
    }

    /**
     * Creates the attribute statement
     */
    protected function createAttributesStatement()
    {
        $connection = Propel::getConnection();
        //The subselect is necessary, cause MySQL does not have the ability to use a join inside an insert
        $this->attributesStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s, %3$s, %4$s, %5$s) VALUES(
                    (SELECT %6$s FROM %7$s WHERE %8$s = :productSku),
                    :fkLocale,
                    :name,
                    :attributes
                ) ON DUPLICATE KEY UPDATE
                    %2$s=VALUES(%2$s),
                    %3$s=VALUES(%3$s),
                    %4$s=VALUES(%4$s),
                    %5$s=VALUES(%5$s);',
                SpyLocalizedProductAttributesTableMap::TABLE_NAME,
                SpyLocalizedProductAttributesTableMap::COL_FK_PRODUCT,
                SpyLocalizedProductAttributesTableMap::COL_FK_LOCALE,
                SpyLocalizedProductAttributesTableMap::COL_NAME,
                SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES,
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::TABLE_NAME,
                SpyProductTableMap::COL_SKU
            )
        );
    }

}
