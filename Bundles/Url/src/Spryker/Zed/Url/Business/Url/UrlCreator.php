<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlCreator extends AbstractUrlCreatorSubject implements UrlCreatorInterface
{
    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlReaderInterface
     */
    protected $urlReader;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlActivatorInterface
     */
    protected $urlActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Url\UrlReaderInterface $urlReader
     * @param \Spryker\Zed\Url\Business\Url\UrlActivatorInterface $urlActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlReaderInterface $urlReader, UrlActivatorInterface $urlActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlReader = $urlReader;
        $this->urlActivator = $urlActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl(UrlTransfer $urlTransfer)
    {
        $this->assertUrlTransferForCreate($urlTransfer);

        $this->urlQueryContainer
            ->getConnection()
            ->beginTransaction();

        $this->notifyBeforeSaveObservers($urlTransfer);

        $urlTransfer = $this->persistUrlEntity($urlTransfer);
        $this->urlActivator->activateUrl($urlTransfer);

        $this->notifyAfterSaveObservers($urlTransfer);

        $this->urlQueryContainer
            ->getConnection()
            ->commit();

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function assertUrlTransferForCreate(UrlTransfer $urlTransfer)
    {
        $urlTransfer
            ->requireUrl()
            ->requireFkLocale();

        $this->assertUrlDoesNotExist($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return void
     */
    protected function assertUrlDoesNotExist(UrlTransfer $urlTransfer)
    {
        if ($this->urlReader->hasUrl($urlTransfer)) {
            throw new UrlExistsException(sprintf(
                'Tried to create url "%s", but it already exists.',
                $urlTransfer->getUrl()
            ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function persistUrlEntity(UrlTransfer $urlTransfer)
    {
        $urlTransfer->requireUrl();
        $urlEntity = $this->urlQueryContainer->queryUrl($urlTransfer->getUrl())->findOneOrCreate();

        $urlEntity->fromArray($urlTransfer->modifiedToArray());
        $urlEntity->save();

        $urlTransfer->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }
}
