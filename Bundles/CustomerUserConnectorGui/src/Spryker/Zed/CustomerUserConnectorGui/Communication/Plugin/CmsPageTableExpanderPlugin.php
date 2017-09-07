<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Plugin;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Spryker\Zed\CmsGui\Dependency\Plugin\CmsPageTableExpanderPluginInterface;

class CmsPageTableExpanderPlugin implements CmsPageTableExpanderPluginInterface
{

    /**
     * @param array $cmsPage
     *
     * @return array
     */
    public function getViewButtonGroupPermanentItems(array $cmsPage)
    {
        return [
            [
                'title' => 'Preview',
                'url' => $this->getPreviewUrl($cmsPage[SpyCmsPageTableMap::COL_ID_CMS_PAGE]),
                'separated' => false,
                'options' => ['target' => '_blank'],
            ],
        ];
    }

    /**
     * @param int $idCmsPage
     *
     * @return string
     */
    protected function getPreviewUrl($idCmsPage)
    {
        // TODO: set Yves host
        $yvesHost = 'http://www.de.project.local';

        return $yvesHost . '/en/cms/preview/' . $idCmsPage;
    }

}
