<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlDeleter implements UrlDeleterInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlActivatorInterface
     */
    protected $urlActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Url\UrlActivatorInterface $urlActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlActivatorInterface $urlActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlActivator = $urlActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        $urlEntity = $this->getUrlEntityToDelete($urlTransfer);

        if (!$urlEntity) {
            return;
        }

        $this->urlQueryContainer->getConnection()->beginTransaction();

        $urlEntity->delete();

        $this->urlActivator->deactivateUrl($urlTransfer);

        // TODO: test and implement redirect cleanup

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl|null
     */
    protected function getUrlEntityToDelete(UrlTransfer $urlTransfer)
    {
        $idUrl = $urlTransfer
            ->requireIdUrl()
            ->getIdUrl();

        return $this->urlQueryContainer
            ->queryUrlById($idUrl)
            ->findOne();
    }

}
