<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Spryker\Zed\Cms\Dependency\CmsVersionPostSavePluginInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class PublishManager implements PublishManagerInterface
{

    const DEFAULT_VERSION_NUMBER = 1;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CmsVersionPostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @param CmsQueryContainerInterface $queryContainer
     * @param CmsVersionPostSavePluginInterface[] $userPlugins
     */
    public function __construct(CmsQueryContainerInterface $queryContainer, array $userPlugins)
    {
        $this->queryContainer = $queryContainer;
        $this->postSavePlugins = $userPlugins;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Exception
     * @return CmsVersionTransfer
     */
    public function publishAndVersionCmsPage($idCmsPage)
    {
        $cmsPageArray = $this->queryContainer->queryCmsPageWithAllRelationsEntitiesByIdPage($idCmsPage)->find()->getFirst();

        if ($cmsPageArray === null) {
            throw new \Exception('There is no CMS page with this id:'. $idCmsPage);
        }

        $cmsVersionTransfer = $this->saveCmsVersion(
            $idCmsPage,
            json_encode($cmsPageArray),
            $this->generateCmsVersion($idCmsPage)
        );

        foreach ($this->postSavePlugins as $userPlugin) {
            $cmsVersionTransfer = $userPlugin->postSave($cmsVersionTransfer);
        }

        return $cmsVersionTransfer;
    }

    /**
     * @param $idCmsPage
     *
     * @return int
     */
    protected function generateCmsVersion($idCmsPage)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        if ($cmsVersionEntity === null) {
            return self::DEFAULT_VERSION_NUMBER;
        }

        return $cmsVersionEntity->getVersion() + 1;
    }

    /**
     * @param int $idCmsPage
     * @param string $data
     * @param int $versionNumber
     *
     * @return CmsVersionTransfer
     */
    protected function saveCmsVersion($idCmsPage, $data, $versionNumber)
    {
        $cmsVersionEntity = new SpyCmsVersion();
        $cmsVersionEntity->setFkCmsPage($idCmsPage);
        $cmsVersionEntity->setData($data);
        $cmsVersionEntity->setVersion($versionNumber);
        $cmsVersionEntity->setVersionName(sprintf('v. %d', $versionNumber));

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
