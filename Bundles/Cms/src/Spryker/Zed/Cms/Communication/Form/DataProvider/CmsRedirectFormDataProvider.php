<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form\DataProvider;

use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsRedirectFormDataProvider
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
     * @param int|null $idUrl
     *
     * @return array
     */
    public function getData($idUrl = null)
    {
        if ($idUrl === null) {
            return [];
        }

        /** @var \Orm\Zed\Url\Persistence\Base\SpyUrl|\Orm\Zed\Url\Persistence\Base\SpyUrlRedirect $urlEntity */
        $urlEntity = $this
            ->cmsQueryContainer
            ->queryUrlByIdWithRedirect($idUrl)
            ->findOne();

        if ($urlEntity === null) {
            return [];
        }

        return [
            CmsRedirectForm::FIELD_FROM_URL => $urlEntity->getUrl(),
            CmsRedirectForm::FIELD_TO_URL => $urlEntity->getToUrl(),
            CmsRedirectForm::FIELD_STATUS => $urlEntity->getStatus(),
        ];
    }

}
