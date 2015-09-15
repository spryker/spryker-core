<?php

namespace SprykerFeature\Zed\Product\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Url\Business\UrlFacade;

class ProductTable extends AbstractTable
{

    const OPTIONS = 'Options';

    /**
     * @var SpyAbstractProductQuery
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
     * @param SpyAbstractProductQuery $productQuery
     */
    public function __construct(SpyAbstractProductQuery $productQuery, UrlFacade $urlFacade, LocaleTransfer $localeTransfer, $yvesUrl)
    {
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
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => 'Id Abstract Product',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
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
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $item[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
                SpyAbstractProductTableMap::COL_SKU => $item[SpyAbstractProductTableMap::COL_SKU],
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
            '<a href="/product/index/view/?id-abstract-product=%d" class="btn btn-sm btn-primary">%s</a>',
            $item[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
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
            ->getUrlByIdAbstractProductAndIdLocale(
                $item[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
                $this->localeTransfer->getIdLocale()
            )
        ;

        return $yvesProductUrl;
    }

}
