<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Spryker\Zed\Url\Business\Exception\MissingRedirectException;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectDeleter implements UrlRedirectDeleterInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    protected $urlRedirectActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface $urlRedirectActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlRedirectActivatorInterface $urlRedirectActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlRedirectActivator = $urlRedirectActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectTransfer->requireIdUrlRedirect();

        $this->urlQueryContainer->getConnection()->beginTransaction();

        $urlRedirectEntity = $this->getRedirectById($urlRedirectTransfer->getIdUrlRedirect());
        $urlRedirectEntity->delete();

        $this->urlRedirectActivator->deactivateUrlRedirect($urlRedirectTransfer);

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idUrlRedirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingRedirectException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function getRedirectById($idUrlRedirect)
    {
        $redirect = $this->urlQueryContainer->queryRedirectById($idUrlRedirect)->findOne();
        if (!$redirect) {
            throw new MissingRedirectException(
                sprintf(
                    'Tried to retrieve a missing redirect with id %s',
                    $idUrlRedirect
                )
            );
        }

        return $redirect;
    }

}
