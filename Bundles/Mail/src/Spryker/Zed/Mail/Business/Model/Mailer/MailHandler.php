<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mailer;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface;

class MailHandler implements MailHandlerInterface
{
    /**
     * @var \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface
     */
    protected $mailBuilder;

    /**
     * @var \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface
     */
    protected $mailProviderCollection;

    /**
     * @var \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface
     */
    protected $mailTypeCollection;

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     * @param \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface $mailCollection
     * @param \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface $mailProviderCollection
     */
    public function __construct(
        MailBuilderInterface $mailBuilder,
        MailTypeCollectionGetInterface $mailCollection,
        MailProviderCollectionGetInterface $mailProviderCollection
    ) {
        $this->mailBuilder = $mailBuilder;
        $this->mailTypeCollection = $mailCollection;
        $this->mailProviderCollection = $mailProviderCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function handleMail(MailTransfer $mailTransfer)
    {
        $mailTypeName = $this->getMailTypeNameFromTransfer($mailTransfer);

        if ($this->mailTypeCollection->has($mailTypeName)) {
            $mailTransfer = $this->buildMail($mailTransfer);
            $this->sendMail($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function buildMail(MailTransfer $mailTransfer)
    {
        $this->mailBuilder->setMailTransfer($mailTransfer);

        $mailType = $this->getMailByType($mailTransfer);
        $mailType->build($this->mailBuilder);

        return $this->mailBuilder->build();
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface
     */
    protected function getMailByType(MailTransfer $mailTransfer)
    {
        $mailTypeName = $this->getMailTypeNameFromTransfer($mailTransfer);

        $mailType = $this->mailTypeCollection->get($mailTypeName);

        return $mailType;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function sendMail(MailTransfer $mailTransfer)
    {
        $mailProviders = $this->getMailProviderByMailType($mailTransfer);

        foreach ($mailProviders as $provider) {
            $provider->sendMail($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface[]
     */
    protected function getMailProviderByMailType(MailTransfer $mailTransfer)
    {
        $mailTypeName = $this->getMailTypeNameFromTransfer($mailTransfer);

        $mailProviders = $this->mailProviderCollection->getProviderForMailType($mailTypeName);

        return $mailProviders;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return string
     */
    protected function getMailTypeNameFromTransfer(MailTransfer $mailTransfer)
    {
        $mailTypeName = $mailTransfer->requireType()->getType();

        return $mailTypeName;
    }
}
