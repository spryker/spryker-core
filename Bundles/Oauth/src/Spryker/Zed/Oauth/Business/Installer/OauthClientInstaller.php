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
        $oauthClientTransfer = new OauthClientTransfer();
        $oauthClientTransfer->setIdentifier(
            $this->oauthConfig->getClientId()
        );

        if (!$this->isExistOauthClient($oauthClientTransfer)) {
            $oauthClientTransfer->setSecret(
                password_hash($this->oauthConfig->getClientSecret(), PASSWORD_BCRYPT)
            );
            $oauthClientTransfer->setIsConfidential(true);
            $oauthClientTransfer->setName('Customer client');

            $this->oauthClientWriter->save($oauthClientTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return bool
     */
    protected function isExistOauthClient(OauthClientTransfer $oauthClientTransfer): bool
    {
        return $this->oauthClientReader->findClientByIdentifier($oauthClientTransfer) !== null;
    }
}
