<?php

namespace Spryker\Zed\Product\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Url\Business\UrlFacade;

class ProductTable extends AbstractTable
{

    const OPTIONS = 'Options';

    /**
     * @var SpyProductAbstractQuery
     */
    protected $productQuery;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string
     */
    protected $yvesUrl;

    /**
     * @param SpyProductAbstractQuery $productQuery
     * @param UrlFacade $urlFacade
     * @param LocaleTransfer $localeTransfer
     * @param $yvesUrl
     */
    public function __construct(
        SpyProductAbstractQuery $productQuery,
        UrlFacade $urlFacade,
        LocaleTransfer $localeTransfer,
        $yvesUrl
    ) {
        $this->productQuery = $productQuery;
        $this->urlFacade = $urlFacade;
        $this->localeTransfer = $localeTransfer;
        $this->yvesUrl = $yvesUrl;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'Id Product Abstract',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            self::OPTIONS => self::OPTIONS,
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
        $queryResults = $this->runQuery($this->productQuery, $config);

        $abstractProducts = [];
        foreach ($queryResults as $item) {
            $abstractProducts[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
                self::OPTIONS => $this->createActionColumn($item),
            ];
        }

        return $abstractProducts;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createActionColumn(array $item)
    {
        $urls = [];

        $urls['viewUrl'] = sprintf(
            '<a href="/product/index/view/?id-product-abstract=%d" class="btn btn-sm btn-primary">%s</a>',
            $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
            'View'
        );

        $urls['yvesProductUrl'] = sprintf(
            '<a href="%s" class="btn btn-sm btn-info" target="_blank">%s</a>',
            $this->yvesUrl . $this->getYvesProductUrl($item)->getUrl(),
            'View in Shop'
        );

        return implode(' ', $urls);
    }

    /**
     * @param array $item
     *
     * @return UrlTransfer
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
