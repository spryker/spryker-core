<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionGenerator implements VersionGeneratorInterface
{
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
    public function generateNewCmsVersion($idCmsPage)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        if ($cmsVersionEntity === null) {
            return self::DEFAULT_VERSION_NUMBER;
        }

        return $cmsVersionEntity->getVersion() + 1;
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateNewCmsVersionName($versionNumber)
    {
        return sprintf('v. %d', $versionNumber);
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateReferenceCmsVersionName($versionNumber)
    {
        return sprintf('copy of v. %d', $versionNumber);
    }
}
