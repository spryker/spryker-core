<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface as ProductSearchExtensionPageMapBuilderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade implements ProductSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link mapDynamicProductAttributesToSearchData()} instead.
     *
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @throws \Spryker\Zed\ProductSearch\Business\Exception\InvalidFilterTypeException
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributes(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $attributes)
    {
        return $this
            ->getFactory()
            ->createProductSearchAttributeMapper()
            ->mapDynamicProductAttributes($pageMapBuilder, $pageMapTransfer, $attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributesToSearchData(
        ProductSearchExtensionPageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        array $attributes
    ): PageMapTransfer {
        return $this->getFactory()
            ->createProductSearchAttributeMapper()
            ->mapDynamicProductAttributesToSearchData($pageMapBuilder, $pageMapTransfer, $attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function activateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->activateProductSearch($idProduct, $localeCollection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function deactivateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->deactivateProductSearch($idProduct, $localeCollection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductSearch(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductSearchWriter()
            ->persistProductSearch($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchPreferencesTransfer
     */
    public function createProductSearchPreferences(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer)
    {
        return $this
            ->getFactory()
            ->createAttributeMapWriter()
            ->create($productSearchPreferencesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchPreferencesTransfer
     */
    public function updateProductSearchPreferences(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer)
    {
        return $this
            ->getFactory()
            ->createAttributeMapWriter()
            ->update($productSearchPreferencesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return void
     */
    public function cleanProductSearchPreferences(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer)
    {
        $this
            ->getFactory()
            ->createAttributeMapWriter()
            ->clean($productSearchPreferencesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedProductSearchAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->suggestUnusedKeys($searchText, $limit);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestProductSearchAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->suggestKeys($searchText, $limit);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function createProductSearchAttribute(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        return $this
            ->getFactory()
            ->createAttributeWriter()
            ->create($productSearchAttributeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function updateProductSearchAttribute(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        return $this
            ->getFactory()
            ->createAttributeWriter()
            ->update($productSearchAttributeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return void
     */
    public function deleteProductSearchAttribute(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        $this
            ->getFactory()
            ->createAttributeWriter()
            ->delete($productSearchAttributeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductSearchAttribute
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer|null
     */
    public function getProductSearchAttribute($idProductSearchAttribute)
    {
        return $this
            ->getFactory()
            ->createAttributeReader()
            ->getAttribute($idProductSearchAttribute);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    public function getProductSearchAttributeList()
    {
        return $this
            ->getFactory()
            ->createAttributeReader()
            ->getAttributeList();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer[] $productSearchAttributes
     *
     * @return void
     */
    public function updateProductSearchAttributeOrder(array $productSearchAttributes)
    {
        $this
            ->getFactory()
            ->createAttributeWriter()
            ->reorder($productSearchAttributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function touchProductAbstractByAsynchronousAttributes()
    {
        $this
            ->getFactory()
            ->createProductSearchAttributeMarker()
            ->touchProductAbstract();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function touchProductAbstractByAsynchronousAttributeMap()
    {
        $this
            ->getFactory()
            ->createProductSearchAttributeMapMarker()
            ->touchProductAbstract();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function touchProductSearchConfigExtension()
    {
        $this
            ->getFactory()
            ->createProductSearchConfigExtensionMarker()
            ->touchProductSearchConfigExtension();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $dataReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function runProductSearchConfigExtensionCollector(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {
        $collector = $this->getFactory()->createProductSearchConfigExtensionCollector();

        $this
            ->getFactory()
            ->getCollectorFacade()
            ->runCollector($collector, $baseQuery, $locale, $result, $dataReader, $dataWriter, $touchUpdater, $output);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function isProductAbstractSearchable($idProductAbstract, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createProductAbstractSearchReader()
            ->isProductAbstractSearchable($idProductAbstract, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function isProductConcreteSearchable($idProductConcrete, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createProductConcreteSearchReader()
            ->isProductConcreteSearchable($idProductConcrete, $localeTransfer);
    }
}
