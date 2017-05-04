<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class VersionPublisher implements VersionPublisherInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Cms\Business\Version\VersionGeneratorInterface
     */
    protected $versionGenerator;

    /**
     * @var \Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface
     */
    protected $versionDataMapper;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Plugin\CmsVersionPostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @param \Spryker\Zed\Cms\Business\Version\VersionGeneratorInterface $versionGenerator
     * @param \Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface $touchFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Cms\Dependency\Plugin\CmsVersionPostSavePluginInterface[] $postSavePlugins
     */
    public function __construct(
        VersionGeneratorInterface $versionGenerator,
        VersionDataMapperInterface $versionDataMapper,
        CmsToTouchInterface $touchFacade,
        CmsQueryContainerInterface $queryContainer,
        array $postSavePlugins
    ) {

        $this->versionGenerator = $versionGenerator;
        $this->versionDataMapper = $versionDataMapper;
        $this->touchFacade = $touchFacade;
        $this->queryContainer = $queryContainer;
        $this->postSavePlugins = $postSavePlugins;
    }

    /**
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function publishAndVersion($idCmsPage, $versionName = null)
    {
        $cmsPageEntity = $this->findCmsPage($idCmsPage);
        $cmsVersionDataTransfer = $this->versionDataMapper->mapToCmsVersionDataTransfer($cmsPageEntity);
        $encodedData = $this->versionDataMapper->mapToJsonData($cmsVersionDataTransfer);

        return $this->createCmsVersion($encodedData, $idCmsPage, $versionName);
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function findCmsPage($idCmsPage)
    {
        $cmsPageCollection = $this->queryContainer
            ->queryCmsPageWithAllRelationsByIdPage($idCmsPage)
            ->find();

        if (empty($cmsPageCollection)) {
            throw new MissingPageException(
                sprintf(
                    'There is no valid Cms page with this id: %d . If the page exists. please check the placeholders',
                    $idCmsPage
                )
            );
        }

        return $cmsPageCollection->getFirst();
    }

    /**
     * @param string $data
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function createCmsVersion($data, $idCmsPage, $versionName = null)
    {
        $versionNumber = $this->versionGenerator->generateNewCmsVersion($idCmsPage);

        if ($versionName === null) {
            $versionName = $this->versionGenerator->generateNewCmsVersionName($versionNumber);
        }

        return $this->saveAndTouchCmsVersion($data, $idCmsPage, $versionName, $versionNumber);
    }

    /**
     * @param string $data
     * @param int $idCmsPage
     * @param string $versionName
     * @param int $versionNumber
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function saveAndTouchCmsVersion($data, $idCmsPage, $versionName, $versionNumber)
    {
        return $this->handleDatabaseTransaction(function () use ($data, $idCmsPage, $versionName, $versionNumber) {
            return $this->executeSaveAndTouchCmsVersionTransaction($data, $idCmsPage, $versionName, $versionNumber);
        });
    }

    /**
     * @param string $data
     * @param int $idCmsPage
     * @param string $versionName
     * @param int $versionNumber
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function executeSaveAndTouchCmsVersionTransaction($data, $idCmsPage, $versionName, $versionNumber)
    {
        $cmsVersionTransfer = $this->saveCmsVersion($idCmsPage, $data, $versionNumber, $versionName);

        foreach ($this->postSavePlugins as $userPlugin) {
            $cmsVersionTransfer = $userPlugin->postSave($cmsVersionTransfer);
        }

        $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $idCmsPage);

        return $cmsVersionTransfer;
    }

    /**
     * @param int $idCmsPage
     * @param string $data
     * @param int $versionNumber
     * @param string $versionName
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function saveCmsVersion($idCmsPage, $data, $versionNumber, $versionName)
    {
        $cmsVersionEntity = new SpyCmsVersion();
        $cmsVersionEntity->setFkCmsPage($idCmsPage);
        $cmsVersionEntity->setData($data);
        $cmsVersionEntity->setVersion($versionNumber);
        $cmsVersionEntity->setVersionName($versionName);
        $cmsVersionEntity->save();

        return $this->versionDataMapper->mapToCmsVersionTransfer($cmsVersionEntity);
    }

}
