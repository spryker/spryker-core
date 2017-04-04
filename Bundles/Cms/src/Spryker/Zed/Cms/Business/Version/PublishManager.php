<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Cms\Dependency\CmsVersionPostSavePluginInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class PublishManager implements PublishManagerInterface
{

    /**
     * @var VersionGeneratorInterface
     */
    protected $versionGenerator;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CmsVersionPostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @param VersionGeneratorInterface $versionGenerator
     * @param CmsQueryContainerInterface $queryContainer
     * @param CmsVersionPostSavePluginInterface[] $postSavePlugins
     */
    public function __construct(VersionGeneratorInterface $versionGenerator, CmsQueryContainerInterface $queryContainer, array $postSavePlugins)
    {
        $this->versionGenerator = $versionGenerator;
        $this->queryContainer = $queryContainer;
        $this->postSavePlugins = $postSavePlugins;
    }

    /**
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @throws \Exception
     * @return CmsVersionTransfer
     */
    public function publishAndVersion($idCmsPage, $versionName = null)
    {
        $cmsPageArray = $this->queryContainer
            ->queryCmsPageWithAllRelationsEntitiesByIdPage($idCmsPage)
            ->find()
            ->toArray(null, false, TableMap::TYPE_COLNAME);

        if (empty($cmsPageArray)) {
            throw new \Exception('There is no draft for CMS page with this id:'. $idCmsPage);
        }

        return $this->createCmsVersion(current($cmsPageArray), $idCmsPage, $versionName);
    }

    /**
     * @param array $data
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @return CmsVersionTransfer
     */
    protected function createCmsVersion(array $data, $idCmsPage, $versionName = null)
    {
        $versionNumber = $this->versionGenerator->generateNewCmsVersion($idCmsPage);

        if ($versionName === null) {
            $versionName = $this->versionGenerator->generateNewCmsVersionName($versionNumber);
        }

        $cmsVersionTransfer = $this->saveCmsVersion(
            $idCmsPage,
            json_encode($data),
            $versionNumber,
            $versionName
        );

        foreach ($this->postSavePlugins as $userPlugin) {
            $cmsVersionTransfer = $userPlugin->postSave($cmsVersionTransfer);
        }

        return $cmsVersionTransfer;
    }

    /**
     * @param int $idCmsPage
     * @param string $data
     * @param int $versionNumber
     * @param string $versionName
     *
     * @return CmsVersionTransfer
     */
    protected function saveCmsVersion($idCmsPage, $data, $versionNumber, $versionName)
    {
        $cmsVersionEntity = new SpyCmsVersion();
        $cmsVersionEntity->setFkCmsPage($idCmsPage);
        $cmsVersionEntity->setData($data);
        $cmsVersionEntity->setVersion($versionNumber);
        $cmsVersionEntity->setVersionName($versionName);
        $cmsVersionEntity->save();

        return $this->convertToCmsVersionTransfer($cmsVersionEntity);
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
}
