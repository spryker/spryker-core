<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorBusinessFactory getFactory()
 */
class CmsBlockCategoryConnectorFacade extends AbstractFacade implements CmsBlockCategoryConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @throws \Spryker\Zed\CmsBlockCategoryConnector\Business\Exception\CmsBlockCategoryPositionNotFound
     *
     * @return void
     */
    public function updateCmsBlockCategoryRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFactory()
            ->createCmsBlockCategoryWrite()
            ->updateCmsBlock($cmsBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\CmsBlockCategoryConnector\Business\Exception\CmsBlockCategoryPositionNotFound
     *
     * @return void
     */
    public function updateCategoryCmsBlockRelations(CategoryTransfer $categoryTransfer)
    {
        $this->getFactory()
            ->createCmsBlockCategoryWrite()
            ->updateCategory($categoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateCmsBlockCategoryRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->getFactory()
            ->createCmsBlockCategoryReader()
            ->hydrateCategoryRelations($cmsBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getRenderedCategoryList($idCmsBlock, $idLocale)
    {
        return $this->getFactory()
            ->createCmsBlockCategoryReader()
            ->getRenderedCategoryList($idCmsBlock, $idLocale);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlockCollection($idCategory, $idCategoryTemplate)
    {
        return $this->getFactory()
            ->createCmsBlockCategoryReader()
            ->getCmsBlockCollection($idCategory, $idCategoryTemplate);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function syncCmsBlockCategoryPosition()
    {
        $this->getFactory()
            ->createCmsBlockCategoryPositionSync()
            ->syncFromConfig();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer|null
     */
    public function findCmsBlockCategoryPositionByName($name)
    {
        return $this->getFactory()
            ->createCmsBlockCategoryPositionReader()
            ->findCmsBlockCategoryPositionByName($name);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $dataReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function runStorageCmsBlockCategoryCollector(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $localeTransfer,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {
        $collector = $this->getFactory()
            ->createStorageCmsBlockCategoryConnectorCollector();

        $this->getFactory()->getCollectorFacade()->runCollector(
            $collector,
            $baseQuery,
            $localeTransfer,
            $result,
            $dataReader,
            $dataWriter,
            $touchUpdater,
            $output
        );
    }
}
