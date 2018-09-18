<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\ProductSetPageSearch\ProductSetPageSearchConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\NamedPageMapInterface;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetPageSearch\Communication\ProductSetPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacadeInterface getFacade()
 */
class ProductSetPageMapPlugin extends AbstractPlugin implements NamedPageMapInterface
{
    const FILTERED_KEYS = [
        'locale',
        'store',
        'type',
    ];

    /**
     * @api
     *
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $data, LocaleTransfer $locale)
    {
        $pageMapTransfer = (new PageMapTransfer())
        ->setStore($data['store'])
        ->setLocale($locale->getLocaleName())
        ->setType('product_set');

        $pageMapBuilder->addIntegerSort($pageMapTransfer, 'weight', $data['weight']);
        $this->mapProductSetStorageTransfer($pageMapTransfer, $pageMapBuilder, $data);

        return $pageMapTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $data
     *
     * @return void
     */
    protected function mapProductSetStorageTransfer(PageMapTransfer $pageMapTransfer, PageMapBuilderInterface $pageMapBuilder, array $data)
    {
        foreach ($data as $key => $value) {
            if ($value !== null && !in_array($key, static::FILTERED_KEYS)) {
                $pageMapBuilder->addSearchResultData($pageMapTransfer, $key, $value);
            }
        }
    }

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return ProductSetPageSearchConstants::PRODUCT_SET_RESOURCE_NAME;
    }
}
