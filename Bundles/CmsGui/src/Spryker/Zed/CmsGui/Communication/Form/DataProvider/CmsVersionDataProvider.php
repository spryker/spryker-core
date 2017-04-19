<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\CmsGui\Communication\Form\Version\CmsVersionFormType;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;

class CmsVersionDataProvider
{

    /**
     * @var CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @param CmsGuiToCmsInterface $cmsFacade
     */
    public function __construct(CmsGuiToCmsInterface $cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param null $idCmsPage
     * @param null $version
     *
     * @return array|CmsVersionTransfer
     */
    public function getData($idCmsPage = null, $version = null)
    {
        if (!$idCmsPage || !$version) {
            return new CmsVersionTransfer();
        } else {
            $cmsVersionTransfer = $this->cmsFacade->findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);
        }

        return $cmsVersionTransfer;
    }

    /**
     * @param int|null $idCmsPage
     *
     * @return array
     */
    public function getOptions($idCmsPage = null)
    {
        return [
            'data_class' => CmsVersionTransfer::class,
            CmsVersionFormType::OPTION_VERSION_NAME_CHOICES => $this->getVersionList($idCmsPage),
        ];
    }

    /**
     * @param int $idCmsPage
     *
     * @return array
     */
    protected function getVersionList($idCmsPage)
    {
        if (!$idCmsPage) {
            return [];
        }

        $cmsVersionTransfers = $this->cmsFacade->findAllCmsVersionByIdCmsPage($idCmsPage);

        $versionList = [];
        foreach ($cmsVersionTransfers as $cmsVersionTransfer) {
            $versionList[$cmsVersionTransfer->getVersion()] = $this->createOptionLabel($cmsVersionTransfer);
        }

        return $versionList;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return string
     */
    protected function createOptionLabel(CmsVersionTransfer $cmsVersionTransfer)
    {
        return sprintf('Version: %s, %s, %s [%s %s]',
            $cmsVersionTransfer->getVersion(),
            $cmsVersionTransfer->getVersionName(),
            date('d/m/Y H:i:s', strtotime($cmsVersionTransfer->getCreatedAt())),
            $cmsVersionTransfer->getFirstName(),
            $cmsVersionTransfer->getLastName()
        );
    }

}
