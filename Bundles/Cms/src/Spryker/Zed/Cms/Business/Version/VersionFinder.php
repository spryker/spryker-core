<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\Base\SpyCmsVersion;
use Spryker\Zed\Cms\Dependency\CmsVersionTransferExpanderPlugin;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionFinder implements VersionFinderInterface
{

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CmsVersionTransferExpanderPlugin[]
     */
    protected $transferExpanderPlugins;

    /**
     * @param CmsQueryContainerInterface $queryContainer
     * @param CmsVersionTransferExpanderPlugin[] $transferExpanderPlugins
     */
    public function __construct(CmsQueryContainerInterface $queryContainer, array $transferExpanderPlugins)
    {
        $this->queryContainer = $queryContainer;
        $this->transferExpanderPlugins = $transferExpanderPlugins;
    }

    /**
     * @param int $idCmsPage
     *
     * @return CmsVersionTransfer|null
     */
    public function findLatestCmsVersionByIdCmsPage($idCmsPage)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        return $this->getCmsVersionTransfer($cmsVersionEntity);
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return CmsVersionTransfer|null
     */
    public function findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPageAndVersion($idCmsPage, $version)->findOne();

        return $this->getCmsVersionTransfer($cmsVersionEntity);
    }

    /**
     * @param SpyCmsVersion $cmsVersionEntity
     *
     * @return CmsVersionTransfer|null
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
     * @param SpyCmsVersion $cmsVersionEntity
     *
     * @return CmsVersionTransfer
     */
    protected function convertToCmsVersionTransfer(SpyCmsVersion $cmsVersionEntity)
    {
        $cmsVersionTransfer = new CmsVersionTransfer();
        $cmsVersionTransfer->fromArray($cmsVersionEntity->toArray(), true);

        return $cmsVersionTransfer;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    protected function expandCmsVersionTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        foreach ($this->transferExpanderPlugins as $transferExpanderPlugin) {
            $cmsVersionTransfer = $transferExpanderPlugin->expandTransfer($cmsVersionTransfer);
        }

        return $cmsVersionTransfer;
    }
}
