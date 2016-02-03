<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\ProductCategory\ProductCategoryConstants;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;

class ProductCategoryTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-category-table';
    const COL_CHECKBOX = 'checkbox';

    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductCategoryQueryContainerInterface $productCategoryQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->locale = $locale;
        $this->idCategory = $idCategory;
        $this->defaultUrl = sprintf('product-category-table?%s=%d', ProductCategoryConstants::PARAM_ID_CATEGORY, $this->idCategory);
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            SpyProductCategoryTableMap::COL_PRODUCT_ORDER => 'Order',
            self::COL_CHECKBOX => 'Selected',
        ]);
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productCategoryQueryContainer->queryProductsByCategoryId($this->idCategory, $this->locale);
        //because datatables won't let use what's already defined in queryProductsByCategoryId()
        //it wil complain that the column <INSERT_NAME> is not found in <table>
        $query->withColumn(
            SpyProductCategoryTableMap::COL_PRODUCT_ORDER,
            'product_order_alias'
        );
        $query->orderBy('product_order_alias', Criteria::ASC);
        $query->setModelAlias('spy_product_abstract');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $productCategory) {
            $results[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $productCategory['id_product_abstract'],
                SpyProductAbstractTableMap::COL_SKU => $productCategory['sku'],
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $productCategory['name'],
                SpyProductCategoryTableMap::COL_PRODUCT_ORDER => $this->getOrderHtml($productCategory),
                self::COL_CHECKBOX => $this->getCheckboxHtml($productCategory),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param $productCategory
     *
     * @return string
     */
    protected function getProductOptionsComboBoxItems($productCategory)
    {
        $preConfigQuery = $this->productCategoryQueryContainer
            ->queryProductCategoryPreConfig($this->idCategory, $productCategory['id_product_abstract'])
            ->orderByFormat();

        $preconfigItems = $preConfigQuery->find();

        $items = '<option value="0">Default</option>';
        foreach ($preconfigItems as $preconfigItem) {
            $selected = '';
            if ((int) $productCategory['preconfig_product'] === (int) $preconfigItem->getIdProduct()) {
                $selected = 'selected="selected"';
            }

            $items .= '<option value="' . $preconfigItem->getIdProduct() . '" ' . $selected . '>' . $preconfigItem->getFormat() . '</option>';
        }

        return $items;
    }

    /**
     * @param array $productCategory
     *
     * @return string
     */
    protected function getCheckboxHtml(array $productCategory)
    {
        return sprintf(
            '<input id="product_category_checkbox_%d" type="checkbox" checked="checked" onclick="categoryTableClickMarkAsSelected(this.checked, %d, \'%s\', \'%s\'); return" /> ',
            $productCategory['id_product_abstract'],
            $productCategory['id_product_abstract'],
            $productCategory['sku'],
            urlencode($productCategory['name'])
        );
    }

    /**
     * @param array $productCategory
     *
     * @return string
     */
    protected function getOrderHtml(array $productCategory)
    {
        return sprintf(
            '<input type="text" value="%d" id="product_category_order_%d" size="4" onchange="updateProductOrder(this, %d)" />',
            $productCategory['product_order'],
            $productCategory['id_product_abstract'],
            $productCategory['id_product_abstract']
        );
    }

}
