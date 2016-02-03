<?php

namespace Spryker\Zed\Product\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Url\Business\UrlFacade;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;

class ProductTable extends AbstractTable
{

    const OPTIONS = 'Options';
    const URL_PRODUCT_INDEX_VIEW = '/product/index/view/';
    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $productQuery;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string
     */
    protected $yvesUrl;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productQuery
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param $yvesUrl
     */
    public function __construct(
        SpyProductAbstractQuery $productQuery,
        ProductToUrlInterface $urlFacade,
        LocaleTransfer $localeTransfer,
        $yvesUrl
    ) {
        $this->productQuery = $productQuery;
        $this->urlFacade = $urlFacade;
        $this->localeTransfer = $localeTransfer;
        $this->yvesUrl = $yvesUrl;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'Id Product Abstract',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            self::OPTIONS => self::OPTIONS,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
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
        $queryResults = $this->runQuery($this->productQuery, $config);

        $productAbstractCollection = [];
        foreach ($queryResults as $item) {
            $productAbstractCollection[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
                self::OPTIONS => implode(' ', $this->createActionColumn($item)),
            ];
        }

        return $productAbstractCollection;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionColumn(array $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate(self::URL_PRODUCT_INDEX_VIEW, [
                self::PARAM_ID_PRODUCT_ABSTRACT => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
            ]),
            'View'
        );

        $urls[] = $this->generateViewButton(
            $this->yvesUrl . $this->getYvesProductUrl($item)->getUrl(),
            'View in Shop',
            [
                'target' => '_blank',
            ]
        );

        return $urls;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getYvesProductUrl(array $item)
    {
        $yvesProductUrl = $this->urlFacade
            ->getUrlByIdProductAbstractAndIdLocale(
                $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                $this->localeTransfer->getIdLocale()
            );

        return $yvesProductUrl;
    }

}
