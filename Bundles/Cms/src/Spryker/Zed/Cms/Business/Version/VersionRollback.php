<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionRollback implements VersionRollbackInterface
{

    /**
     * @var VersionPublisherInterface
     */
    protected $versionPublisher;

    /**
     * @var VersionGeneratorInterface
     */
    protected $versionGenerator;

    /**
     * @var VersionMigrationInterface
     */
    protected $versionMigration;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param VersionPublisherInterface $versionPublisher
     * @param VersionGeneratorInterface $versionGenerator
     * @param VersionMigrationInterface $versionMigration
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(
        VersionPublisherInterface $versionPublisher,
        VersionGeneratorInterface $versionGenerator,
        VersionMigrationInterface $versionMigration,
        CmsQueryContainerInterface $queryContainer)
    {
        $this->versionPublisher = $versionPublisher;
        $this->versionGenerator = $versionGenerator;
        $this->versionMigration = $versionMigration;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @throws MissingPageException
     *
     * @return bool
     */
    public function rollback($idCmsPage, $version)
    {
        $originVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();
        $targetVersionEntity = $this->queryContainer->queryCmsVersionByIdPageAndVersion($idCmsPage, $version)->findOne();

        if ($originVersionEntity === null || $targetVersionEntity === null) {
            throw new MissingPageException(
                sprintf(
                    "There is no valid Cms page with this id: %d or Cms version with this version: %d for rollback",
                    $idCmsPage,
                    $version
                ));
        }

        if (!$this->versionMigration->migrate($originVersionEntity->getData(), $targetVersionEntity->getData())) {
            return false;
        }

        $this->versionPublisher->publishAndVersion(
            $idCmsPage,
            $this->versionGenerator->generateReferenceCmsVersionName($version)
        );

        return true;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws MissingPageException
     *
     * @return bool
     */
    public function revert($idCmsPage)
    {
        $versionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        if ($versionEntity === null) {
            throw new MissingPageException(
                sprintf(
                    "There is no valid Cms version with this id: %d for reverting",
                    $idCmsPage
                ));
        }

        $latestVersionData = $versionEntity->getData();

        if (!$this->versionMigration->migrate($latestVersionData, $latestVersionData)) {
            return false;
        }

        return true;
    }

}
