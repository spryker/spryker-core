<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionGenerator implements VersionGeneratorInterface
{
    /**
     * @var int
     */
    public const DEFAULT_VERSION_NUMBER = 1;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return int
     */
    public function generateNewCmsVersion(int $idCmsPage): int
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        if ($cmsVersionEntity === null) {
            return static::DEFAULT_VERSION_NUMBER;
        }

        return $cmsVersionEntity->getVersion() + 1;
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateNewCmsVersionName(int $versionNumber): string
    {
        return sprintf('v. %d', $versionNumber);
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateReferenceCmsVersionName(int $versionNumber): string
    {
        return sprintf('copy of v. %d', $versionNumber);
    }
}
