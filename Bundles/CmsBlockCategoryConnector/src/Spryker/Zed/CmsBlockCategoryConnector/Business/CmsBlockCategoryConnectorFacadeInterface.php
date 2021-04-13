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
use Symfony\Component\Console\Output\OutputInterface;

interface CmsBlockCategoryConnectorFacadeInterface
{
    /**
     * Specification:
     * - Perform actions based on CMS Block transfer
     * - Delete all relations categories to cms blocks
     * - Add new relations defined in the transfer object
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @throws \Spryker\Zed\CmsBlockCategoryConnector\Business\Exception\CmsBlockCategoryPositionNotFound
     *
     * @return void
     */
    public function updateCmsBlockCategoryRelations(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * Specification:
     * - Perform actions based on Category transfer
     * - Delete all relations categories to cms blocks
     * - Add new relations defined in the transfer object
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\CmsBlockCategoryConnector\Business\Exception\CmsBlockCategoryPositionNotFound
     *
     * @return void
     */
    public function updateCategoryCmsBlockRelations(CategoryTransfer $categoryTransfer);

    /**
     * Specification:
     * - Hydrate Cms Block with an array of related category IDs
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateCmsBlockCategoryRelations(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * Specification:
     * - Generate rendered list of assigned categories
     *
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getRenderedCategoryList($idCmsBlock, $idLocale);

    /**
     * Specification:
     * - Get collection of related CMS Blocks
     *
     * @api
     *
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlockCollection($idCategory, $idCategoryTemplate);

    /**
     * Specification:
     * - Takes positions from configuration
     * - Creates new CMS Block category position records
     * - Does not remove/update existing records
     *
     * @api
     *
     * @return void
     */
    public function syncCmsBlockCategoryPosition();

    /**
     * Specification:
     * - Finds a position by name
     * - Hydrates transfer object
     * - Returns NULL if position does not exist
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer|null
     */
    public function findCmsBlockCategoryPositionByName($name);

    /**
     * Specification:
     * - Hydrate CMS Block to Category relation with block names
     * - Collect relation to Storage
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
    );

    /**
     * Specification:
     * - Gets collection of related CMS Blocks ids and names.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return string[]
     */
    public function getCmsBlockNamesIndexedByCmsBlockIdsForCategory(CategoryTransfer $categoryTransfer): array;
}
