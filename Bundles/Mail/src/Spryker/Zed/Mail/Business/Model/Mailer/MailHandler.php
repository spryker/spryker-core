<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mailer;

use Generated\Shared\Transfer\MailSenderTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface;
use Spryker\Zed\Mail\MailConfig;

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
     * @deprecated Use {@link \Spryker\Zed\Mail\Business\Model\Mailer\MailHandler::$mailTypeBuilderPlugins} instead.
     *
     * @var \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface
     */
    protected $mailTypeCollection;

    /**
     * @var array<\Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface>
     */
    protected $mailTypeBuilderPlugins = [];

    /**
     * @var \Spryker\Zed\Mail\MailConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     * @param \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface $mailCollection
     * @param \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface $mailProviderCollection
     * @param array<\Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface> $mailTypeBuilderPlugins
     * @param \Spryker\Zed\Mail\MailConfig $config
     */
    public function __construct(
        MailBuilderInterface $mailBuilder,
        MailTypeCollectionGetInterface $mailCollection,
        MailProviderCollectionGetInterface $mailProviderCollection,
        array $mailTypeBuilderPlugins,
        MailConfig $config
    ) {
        $this->mailBuilder = $mailBuilder;
        $this->mailTypeCollection = $mailCollection;
        $this->mailProviderCollection = $mailProviderCollection;
        $this->mailTypeBuilderPlugins = $mailTypeBuilderPlugins;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function handleMail(MailTransfer $mailTransfer)
    {
        $isMailTypeBuilderPluginExisted = false;

        foreach ($this->mailTypeBuilderPlugins as $mailTypeBuilderPlugin) {
            if ($mailTypeBuilderPlugin->getName() !== $this->getMailTypeNameFromTransfer($mailTransfer)) {
                continue;
            }

            $isMailTypeBuilderPluginExisted = true;
            $mailTransfer = $this->setDefaultSender($mailTransfer);
            $mailTransfer = $mailTypeBuilderPlugin->build($mailTransfer);
            $this->sendMail($mailTransfer);
        }

        if ($isMailTypeBuilderPluginExisted === false) {
            $this->handleByMailTypePlugin($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function setDefaultSender(MailTransfer $mailTransfer): MailTransfer
    {
        $mailSenderTransfer = (new MailSenderTransfer())
            ->setEmail($this->config->getSenderEmail())
            ->setName($this->config->getSenderName());

        return $mailTransfer->setSender($mailSenderTransfer);
    }

    /**
     * @deprecated Will be removed in the next major. Exists for BC-reason only.
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function handleByMailTypePlugin(MailTransfer $mailTransfer): void
    {
        if ($this->mailTypeCollection->has($this->getMailTypeNameFromTransfer($mailTransfer))) {
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
     * @return array<\Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface>
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
