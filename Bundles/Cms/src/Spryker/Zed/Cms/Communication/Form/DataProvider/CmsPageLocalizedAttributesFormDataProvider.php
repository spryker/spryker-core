<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form\DataProvider;

use Orm\Zed\Cms\Persistence\Base\SpyCmsPageLocalizedAttributesQuery;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageLocalizedAttributesFormDataProvider
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param int|null $idCmsPage
     * @param int|null $idLocale
     *
     * @return array
     */
    public function getData($idCmsPage = null, $idLocale = null)
    {
        if ($idCmsPage === null || $idLocale === null) {
            return [];
        }

        $cmsPageLocalizedAttributesEntity = SpyCmsPageLocalizedAttributesQuery::create()
            ->filterByFkCmsPage($idCmsPage)
            ->filterByFkLocale($idLocale)
            ->findOne();

        if (!$cmsPageLocalizedAttributesEntity) {
            return [];
        }

        return $cmsPageLocalizedAttributesEntity->toArray();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
