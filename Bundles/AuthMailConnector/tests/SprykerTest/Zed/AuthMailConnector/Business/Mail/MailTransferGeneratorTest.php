<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthMailConnector\Business\Mail;

use Codeception\Test\Unit;
use Spryker\Shared\AuthMailConnector\AuthMailConnectorConstants;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailBuilder;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailBuilderInterface;
use Spryker\Zed\AuthMailConnector\Communication\Plugin\Mail\RestorePasswordMailTypePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AuthMailConnector
 * @group Business
 * @group Mail
 * @group MailBuilderTest
 * Add your own group annotations below this line
 */
class MailBuilderTest extends Unit
{
    protected const EMAIL = 'test@test.com';
    protected const TOKEN = 'token';

    protected const BASE_URL_ZED = 'http://zed.de.shop.local';

    protected const EXPECTED_RECIPIENTS_COUNT = 1;
    protected const EXPECTED_RESET_PASSWORD_LINK_FORMAT = '%s/auth/password/reset?token=%s';

    /**
     * @var \SprykerTest\Zed\AuthMailConnector\AuthMailConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnCorrectResetPasswordLink(): void
    {
        $this->tester->setConfig(AuthMailConnectorConstants::BASE_URL_ZED, static::BASE_URL_ZED);

        $resetPasswordMailTransfer = $this->createMailBuilder()->buildResetPasswordMailTransfer(static::EMAIL, static::TOKEN);
        $expectedResetPasswordLink = sprintf(static::EXPECTED_RESET_PASSWORD_LINK_FORMAT, static::BASE_URL_ZED, static::TOKEN);

        $this->assertSame($expectedResetPasswordLink, $resetPasswordMailTransfer->getResetPasswordLink());
    }

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnCorrectMailType(): void
    {
        $resetPasswordMailTransfer = $this->createMailBuilder()->buildResetPasswordMailTransfer(static::EMAIL, static::TOKEN);

        $this->assertSame(RestorePasswordMailTypePlugin::MAIL_TYPE, $resetPasswordMailTransfer->getType());
    }

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnCorrectRecipient(): void
    {
        $resetPasswordMailTransfer = $this->createMailBuilder()->buildResetPasswordMailTransfer(static::EMAIL, static::TOKEN);

        $this->assertSame(static::EXPECTED_RECIPIENTS_COUNT, $resetPasswordMailTransfer->getRecipients()->count());
        /** @var \Generated\Shared\Transfer\MailRecipientTransfer $mailRecipientTransfer */
        $mailRecipientTransfer = $resetPasswordMailTransfer->getRecipients()[0];
        $this->assertSame(static::EMAIL, $mailRecipientTransfer->getEmail());
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\Business\Mail\MailBuilderInterface
     */
    protected function createMailBuilder(): MailBuilderInterface
    {
        return new MailBuilder(
            $this->createAuthMailConnectorConfig()
        );
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig
     */
    protected function createAuthMailConnectorConfig(): AuthMailConnectorConfig
    {
        return new AuthMailConnectorConfig();
    }
}
