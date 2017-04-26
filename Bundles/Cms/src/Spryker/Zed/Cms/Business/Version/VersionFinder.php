<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\Base\SpyCmsVersion;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionFinder implements VersionFinderInterface
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\CmsVersionTransferExpanderPlugin[]
     */
    protected $transferExpanderPlugins;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Cms\Dependency\CmsVersionTransferExpanderPlugin[] $transferExpanderPlugins
     */
    public function __construct(CmsQueryContainerInterface $queryContainer, array $transferExpanderPlugins)
    {
        $this->queryContainer = $queryContainer;
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
     * @return array
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
     * @param \Orm\Zed\Cms\Persistence\Base\SpyCmsVersion $cmsVersionEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    protected function getCmsVersionTransfer($cmsVersionEntity)
    {
        if ($cmsVersionEntity === null) {
            return null;
        }

        $cmsVersionTransfer = $this->convertToCmsVersionTransfer($cmsVersionEntity);

        return $this->expandCmsVersionTransfer($cmsVersionTransfer);
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\Base\SpyCmsVersion $cmsVersionEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function convertToCmsVersionTransfer(SpyCmsVersion $cmsVersionEntity)
    {
        $cmsVersionTransfer = new CmsVersionTransfer();
        $cmsVersionTransfer->fromArray($cmsVersionEntity->toArray(), true);

        return $cmsVersionTransfer;
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

}
