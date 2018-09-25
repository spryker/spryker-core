<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionFinder implements VersionFinderInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface
     */
    protected $versionDataMapper;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Plugin\CmsVersionTransferExpanderPluginInterface[]
     */
    protected $transferExpanderPlugins;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface $versionDataMapper
     * @param \Spryker\Zed\Cms\Dependency\Plugin\CmsVersionTransferExpanderPluginInterface[] $transferExpanderPlugins
     */
    public function __construct(
        CmsQueryContainerInterface $queryContainer,
        VersionDataMapperInterface $versionDataMapper,
        array $transferExpanderPlugins
    ) {

        $this->queryContainer = $queryContainer;
        $this->versionDataMapper = $versionDataMapper;
        $this->transferExpanderPlugins = $transferExpanderPlugins;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    public function findLatestCmsVersionByIdCmsPage($idCmsPage)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        return $this->getCmsVersionTransfer($cmsVersionEntity);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer[]
     */
    public function findAllCmsVersionByIdCmsPage($idCmsPage)
    {
        $cmsVersionEntities = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->find();

        $cmsVersionTransfers = [];
        foreach ($cmsVersionEntities as $cmsVersionEntity) {
            $cmsVersionTransfers[] = $this->getCmsVersionTransfer($cmsVersionEntity);
        }

        return $cmsVersionTransfers;
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    public function findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPageAndVersion($idCmsPage, $version)->findOne();

        return $this->getCmsVersionTransfer($cmsVersionEntity);
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsVersion|null $cmsVersionEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    protected function getCmsVersionTransfer($cmsVersionEntity)
    {
        if ($cmsVersionEntity === null) {
            return null;
        }

        $cmsVersionTransfer = $this->versionDataMapper->mapToCmsVersionTransfer($cmsVersionEntity);

        return $this->expandCmsVersionTransfer($cmsVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function expandCmsVersionTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        foreach ($this->transferExpanderPlugins as $transferExpanderPlugin) {
            $cmsVersionTransfer = $transferExpanderPlugin->expandTransfer($cmsVersionTransfer);
        }

        return $cmsVersionTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData($idCmsPage)
    {
        $cmsPageEntity = $this->getCmsPage($idCmsPage);
        $cmsVersionDataTransfer = $this->versionDataMapper->mapToCmsVersionDataTransfer($cmsPageEntity);

        return $cmsVersionDataTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function getCmsPage($idCmsPage)
    {
        $cmsPageCollection = $this->queryContainer
            ->queryCmsPageWithAllRelationsByIdPage($idCmsPage)
            ->find();

        if ($cmsPageCollection->count() === 0) {
            throw new MissingPageException(
                sprintf(
                    'There is no valid Cms page with this id: %d . If the page exists. please check the placeholders',
                    $idCmsPage
                )
            );
        }

        return $cmsPageCollection->getFirst();
    }
}
