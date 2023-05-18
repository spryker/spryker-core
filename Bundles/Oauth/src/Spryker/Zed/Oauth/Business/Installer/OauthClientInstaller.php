<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Installer;

use Generated\Shared\Transfer\OauthClientTransfer;
use Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface;
use Spryker\Zed\Oauth\OauthConfig;

class OauthClientInstaller implements OauthClientInstallerInterface
{
    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface
     */
    protected $oauthClientWriter;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface
     */
    protected $oauthClientReader;

    /**
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface $oauthClientWriter
     * @param \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface $oauthClientReader
     */
    public function __construct(
        OauthConfig $oauthConfig,
        OauthClientWriterInterface $oauthClientWriter,
        OauthClientReaderInterface $oauthClientReader
    ) {
        $this->oauthConfig = $oauthConfig;
        $this->oauthClientWriter = $oauthClientWriter;
        $this->oauthClientReader = $oauthClientReader;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        if (!$this->oauthConfig->getClientConfiguration()) {
            $this->saveClientFallback();

            return;
        }

        foreach ($this->oauthConfig->getClientConfiguration() as $clientConfiguration) {
            $this->saveClient($clientConfiguration);
        }
    }

    /**
     * @param array<string, mixed> $clientConfiguration
     *
     * @return void
     */
    protected function saveClient(array $clientConfiguration): void
    {
        $oauthClientTransfer = (new OauthClientTransfer())
            ->fromArray($clientConfiguration, true);

        if ($this->oauthClientReader->findClientByIdentifier($oauthClientTransfer)) {
            return;
        }

        /** @var string $secret */
        $secret = password_hash($oauthClientTransfer->getSecret(), PASSWORD_BCRYPT);

        $oauthClientTransfer
            ->setSecret($secret);

        if ($oauthClientTransfer->getIsConfidential() === null) {
            $oauthClientTransfer->setIsConfidential(true);
        }

        $this->oauthClientWriter->save($oauthClientTransfer);
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @return void
     */
    protected function saveClientFallback(): void
    {
        $oauthClientTransfer = (new OauthClientTransfer())
            ->setIdentifier($this->oauthConfig->getClientId());

        if ($this->oauthClientReader->findClientByIdentifier($oauthClientTransfer)) {
            return;
        }

        /** @var string $secret */
        $secret = password_hash($this->oauthConfig->getClientSecret(), PASSWORD_BCRYPT);

        $oauthClientTransfer
            ->setSecret($secret)
            ->setIsConfidential(true)
            ->setName('Customer client');

        $this->oauthClientWriter->save($oauthClientTransfer);
    }
}
