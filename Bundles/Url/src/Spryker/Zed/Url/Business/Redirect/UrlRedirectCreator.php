<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Url\Business\Url\UrlCreatorInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectCreator implements UrlRedirectCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlCreatorInterface
     */
    protected $urlCreator;

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    protected $urlRedirectActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Url\UrlCreatorInterface $urlCreator
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface $urlRedirectActivator
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlCreatorInterface $urlCreator,
        UrlRedirectActivatorInterface $urlRedirectActivator
    ) {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlCreator = $urlCreator;
        $this->urlRedirectActivator = $urlRedirectActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function createUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $this->assertUrlRedirectTransfer($urlRedirectTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($urlRedirectTransfer): UrlRedirectTransfer {
            return $this->executeCreateUrlTransaction($urlRedirectTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    protected function executeCreateUrlTransaction(UrlRedirectTransfer $urlRedirectTransfer): UrlRedirectTransfer
    {
        $urlRedirectTransfer = $this->persistUrlRedirectEntity($urlRedirectTransfer);
        $this->urlRedirectActivator->activateUrlRedirect($urlRedirectTransfer);

        return $urlRedirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    protected function assertUrlRedirectTransfer(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectTransfer
            ->requireSource()
            ->requireToUrl()
            ->requireStatus();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    protected function persistUrlRedirectEntity(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity->fromArray($urlRedirectTransfer->toArray());
        $urlRedirectEntity->save();

        $urlRedirectTransfer->fromArray($urlRedirectEntity->toArray(), true);

        $sourceUrlTransfer = $urlRedirectTransfer->getSource();
        $sourceUrlTransfer->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect());

        $sourceUrlTransfer = $this->urlCreator->createUrl($sourceUrlTransfer);
        $urlRedirectTransfer->setSource($sourceUrlTransfer);

        return $urlRedirectTransfer;
    }
}
