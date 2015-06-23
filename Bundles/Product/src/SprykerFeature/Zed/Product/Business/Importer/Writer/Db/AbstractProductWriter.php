<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Writer\Db;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Product\Business\Importer\Writer\AbstractProductWriterInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;

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
    protected $localeTransfer;

    /**
     * @param LocaleTransfer $localeTransfer
     */
    public function __construct(LocaleTransfer $localeTransfer)
    {
        $this->localeTransfer = $localeTransfer;
        $this->createProductStatement();
        $this->createAttributeStatement();
    }

    /**
     * @param AbstractProductTransfer $product
     *
     * @return bool
     */
    public function writeAbstractProduct(AbstractProductTransfer $product)
    {
         return (
            $this->productStatement->execute([':sku' => $product->getSku()]) &&
            $this->attributesStatement->execute(
                [
                    ':attributes' => json_encode($product->getAttributes()),
                    ':name' => $product->getName(),
                    ':abstractProductSku' => $product->getSku(),
                    ':fkLocale' => $this->localeTransfer->getIdLocale()
                ]
            )
         );
    }

    protected function createProductStatement()
    {
        $connection = Propel::getConnection();
        $this->productStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s) VALUES (:sku)
                ON DUPLICATE KEY UPDATE %2$s=VALUES(%2$s);',
                SpyAbstractProductTableMap::TABLE_NAME,
                SpyAbstractProductTableMap::COL_SKU
            )
        );
    }

    protected function createAttributeStatement()
    {
        $connection = Propel::getConnection();
        $this->attributesStatement = $connection->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s, %3$s, %4$s, %5$s) VALUES(
                    (SELECT %6$s FROM %7$s WHERE %8$s = :abstractProductSku),
                    :fkLocale,
                    :name,
                    :attributes
                ) ON DUPLICATE KEY UPDATE
                    %2$s=VALUES(%2$s),
                    %3$s=VALUES(%3$s),
                    %4$s=VALUES(%4$s),
                    %5$s=VALUES(%5$s);',
                SpyLocalizedAbstractProductAttributesTableMap::TABLE_NAME,
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_ABSTRACT_PRODUCT,
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_LOCALE,
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
                SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                SpyAbstractProductTableMap::TABLE_NAME,
                SpyAbstractProductTableMap::COL_SKU
            )
        );
    }
}
