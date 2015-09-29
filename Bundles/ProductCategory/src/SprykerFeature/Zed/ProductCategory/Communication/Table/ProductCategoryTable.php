<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\Map\SpyProductCategoryTableMap;


class ProductCategoryTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'product-category-table';

    const COL_CHECKBOX = 'checkbox';

    /**
     * @var ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;


    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @param ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductCategoryQueryContainerInterface $productCategoryQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->locale = $locale;
        $this->idCategory = $idCategory;
        $this->defaultUrl = sprintf('productCategoryTable?%s=%d', ProductCategoryConfig::PARAM_ID_CATEGORY, $this->idCategory);
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => 'ID',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => 'Name',
            SpyProductCategoryTableMap::COL_PRODUCT_ORDER => 'Order',
            self::COL_CHECKBOX => 'Selected',
        ]);
        $config->setSearchable([
            SpyAbstractProductTableMap::COL_SKU,
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productCategoryQueryContainer->queryProductsByCategoryId($this->idCategory, $this->locale);
        $query->withColumn(
            SpyProductCategoryTableMap::COL_PRODUCT_ORDER,
            'product_order_alias' //because datatables
        );
        $query->orderBy('product_order_alias', Criteria::DESC);
        $query->setModelAlias('spy_abstract_product');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $productCategory) {
            //TODO fix when properly implementing product preconfig
            //https://kartenmacherei.atlassian.net/browse/KSP-877
            /*
               $items = $this->getProductOptionsComboBoxItems($productCategory);

                $select_html = sprintf(
                    '<select id="product_category_preconfig_%d" onchange="updateProductCategoryPreconfig(this, %d)">%s</select>',
                    $productCategory['id_abstract_product'],
                    $productCategory['id_abstract_product'],
                    $items
                );
            */

            $results[] = [
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $productCategory['id_abstract_product'],
                SpyAbstractProductTableMap::COL_SKU => $productCategory['sku'],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $productCategory['name'],
                SpyProductCategoryTableMap::COL_PRODUCT_ORDER => $this->getOrderHtml($productCategory),
                self::COL_CHECKBOX => $this->getCheckboxHtml($productCategory)
            ];
        }
        unset($queryResults);
        return $results;
    }

    /**
     * @param $productCategory
     * @return string
     */
    protected function getProductOptionsComboBoxItems($productCategory)
    {
        $preconfigQuery = $this->productCategoryQueryContainer
            ->queryProductCategoryPreconfig($this->idCategory, $productCategory['id_abstract_product'])
            ->orderByFormat();

        $preconfigItems = $preconfigQuery->find();

        $items = '<option value="0">Default</option>';
        foreach ($preconfigItems as $preconfigItem) {
            $selected = '';
            if ((int) $productCategory['preconfig_product'] === (int) $preconfigItem->getIdProduct()) {
                $selected = 'selected="selected"';
            }

            $items .= '<option value="'.$preconfigItem->getIdProduct().'" '.$selected.'>'.$preconfigItem->getFormat().'</option>';
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
        return  sprintf(
            '<input id="product_category_checkbox_%d" type="checkbox" checked="checked" onclick="categoryTableClickMarkAsSelected(this.checked, %d, \'%s\', \'%s\'); return" /> ',
            $productCategory['id_abstract_product'],
            $productCategory['id_abstract_product'],
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
            $productCategory['id_abstract_product'],
            $productCategory['id_abstract_product']
        );
    }
}
