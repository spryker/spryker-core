<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Url\Business\Exception\MissingRedirectException;
use Spryker\Zed\Url\Business\Url\UrlUpdaterInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectUpdater implements UrlRedirectUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlUpdaterInterface
     */
    protected $urlUpdater;

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    protected $urlRedirectActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Url\UrlUpdaterInterface $urlUpdater
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface $urlRedirectActivator
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlUpdaterInterface $urlUpdater,
        UrlRedirectActivatorInterface $urlRedirectActivator
    ) {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlUpdater = $urlUpdater;
        $this->urlRedirectActivator = $urlRedirectActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function updateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectTransfer->requireIdUrlRedirect();

        return $this->getTransactionHandler()->handleTransaction(function () use ($urlRedirectTransfer): UrlRedirectTransfer {
            return $this->executeUpdateUrlRedirectTransaction($urlRedirectTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    protected function executeUpdateUrlRedirectTransaction(UrlRedirectTransfer $urlRedirectTransfer): UrlRedirectTransfer
    {
        $urlRedirectEntity = $this->getRedirectById($urlRedirectTransfer->getIdUrlRedirect());
        $urlRedirectEntity->fromArray($urlRedirectTransfer->modifiedToArray());
        $urlRedirectEntity->save();
        $urlRedirectTransfer->fromArray($urlRedirectEntity->toArray(), true);

        $sourceUrlTransfer = $urlRedirectTransfer->getSource();
        if ($sourceUrlTransfer) {
            $sourceUrlTransfer = $this->urlUpdater->updateUrl($sourceUrlTransfer);
            $urlRedirectTransfer->setSource($sourceUrlTransfer);
        }

        $this->urlRedirectActivator->activateUrlRedirect($urlRedirectTransfer);

        return $urlRedirectTransfer;
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
        $urlRedirectEntity = $this->urlQueryContainer->queryRedirectById($idUrlRedirect)->findOne();

        if (!$urlRedirectEntity) {
            throw new MissingRedirectException(sprintf(
                'Tried to retrieve a missing url redirect entity with ID %s.',
                $idUrlRedirect,
            ));
        }

        return $urlRedirectEntity;
    }
}
