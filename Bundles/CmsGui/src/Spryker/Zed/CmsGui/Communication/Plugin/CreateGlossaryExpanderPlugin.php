<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\CmsGui\Dependency\Plugin\CreateGlossaryExpanderPluginInterface;

class CreateGlossaryExpanderPlugin implements CreateGlossaryExpanderPluginInterface
{

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getViewActionButtons(CmsPageTransfer $cmsPageTransfer)
    {
        return [
            $this->getPreviewActionButton($cmsPageTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function getPreviewActionButton(CmsPageTransfer $cmsPageTransfer)
    {
        return (new ButtonTransfer())
            ->setUrl($this->getPreviewPageUrl($cmsPageTransfer->getFkPage()))
            ->setTitle('Preview')
            ->setDefaultOptions(['target' => '_blank']);
    }

    /**
     * @param int $idCmsPage
     *
     * @return string
     */
    protected function getPreviewPageUrl($idCmsPage)
    {
        // TODO: solve this
        $yvesHost = 'http://www.de.project.local';

        return $previewPageUrl = $yvesHost . '/en/cms/preview/' . $idCmsPage;
    }

}
