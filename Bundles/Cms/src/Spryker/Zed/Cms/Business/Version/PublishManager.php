<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsPageVersionTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPageVersion;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class PublishManager implements PublishManagerInterface
{

    const DEFAULT_VERSION_NUMBER = 1;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Exception
     * @return CmsPageVersionTransfer
     */
    public function publishAndVersionCmsPage($idCmsPage)
    {
        $cmsPageArray = $this->queryContainer->queryCmsPageWithAllRelationsEntitiesByIdPage($idCmsPage)->find()->getFirst();

        if ($cmsPageArray === null) {
            throw new \Exception('There is no Cms page with this id:'. $idCmsPage);
        }

        return $this->saveCmsPageVersion(
            $idCmsPage,
            json_encode($cmsPageArray),
            $this->generateCmsPageVersion($idCmsPage)
        );
    }

    /**
     * @param $idCmsPage
     *
     * @return int
     */
    protected function generateCmsPageVersion($idCmsPage)
    {
        $cmsPageVersion = $this->queryContainer->queryCmsPageVersionByIdPage($idCmsPage)->findOne();

        if ($cmsPageVersion === null) {
            return self::DEFAULT_VERSION_NUMBER;
        }

        return $cmsPageVersion->getVersion() + 1;
    }

    /**
     * @param int $idCmsPage
     * @param string $data
     * @param int $versionNumber
     *
     * @return CmsPageVersionTransfer
     */
    protected function saveCmsPageVersion($idCmsPage, $data, $versionNumber)
    {
        $cmsPageVersionEntity = new SpyCmsPageVersion();
        $cmsPageVersionEntity->setFkCmsPage($idCmsPage);
        $cmsPageVersionEntity->setData($data);
        $cmsPageVersionEntity->setVersion($versionNumber);
        $cmsPageVersionEntity->setVersionName(sprintf('v. %d', $versionNumber));

        $cmsPageVersionEntity->save();

        return $this->convertToCmsPageVersionTransfer($cmsPageVersionEntity);
    }

    /**
     * @param SpyCmsPageVersion $cmsPageVersionEntity
     *
     * @return CmsPageVersionTransfer
     */
    protected function convertToCmsPageVersionTransfer(SpyCmsPageVersion $cmsPageVersionEntity)
    {
        $cmsPageVersionTransfer = new CmsPageVersionTransfer();
        $cmsPageVersionTransfer->fromArray($cmsPageVersionEntity->toArray(), true);

        return $cmsPageVersionTransfer;
    }
}
