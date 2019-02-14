<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthMailConnector\Business\Mail;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGenerator;
use Spryker\Zed\AuthMailConnector\Communication\Plugin\Mail\RestorePasswordMailTypePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AuthMailConnector
 * @group Business
 * @group Mail
 * @group MailTransferGeneratorTest
 * Add your own group annotations below this line
 */
class MailTransferGeneratorTest extends Unit
{
    protected const EMAIL = 'test@test.com';
    protected const TOKEN = 'token';

    protected const BASE_URL_ZED = 'http://zed.de.shop.local';

    protected const EXPECTED_RECIPIENTS_COUNT = 1;
    protected const EXPECTED_RESET_PASSWORD_LINK_FORMAT = '%s/auth/password/reset?token=%s';

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnMailTransfer(): void
    {
        $resetPasswordMailTransfer = $this->createMailTransferGenerator()->generateResetPasswordMailTransfer(static::EMAIL, static::TOKEN);

        $this->assertInstanceOf(MailTransfer::class, $resetPasswordMailTransfer);
    }

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnCorrectResetPasswordLink(): void
    {
        $resetPasswordMailTransfer = $this->createMailTransferGenerator()->generateResetPasswordMailTransfer(static::EMAIL, static::TOKEN);
        $expectedResetPasswordLink = sprintf(static::EXPECTED_RESET_PASSWORD_LINK_FORMAT, static::BASE_URL_ZED, static::TOKEN);

        $this->assertSame($expectedResetPasswordLink, $resetPasswordMailTransfer->getResetPasswordLink());
    }

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnCorrectMailType(): void
    {
        $resetPasswordMailTransfer = $this->createMailTransferGenerator()->generateResetPasswordMailTransfer(static::EMAIL, static::TOKEN);

        $this->assertSame(RestorePasswordMailTypePlugin::MAIL_TYPE, $resetPasswordMailTransfer->getType());
    }

    /**
     * @return void
     */
    public function testGenerateResetPasswordMailTransferReturnCorrectRecipient(): void
    {
        $resetPasswordMailTransfer = $this->createMailTransferGenerator()->generateResetPasswordMailTransfer(static::EMAIL, static::TOKEN);

        $this->assertSame(static::EXPECTED_RECIPIENTS_COUNT, $resetPasswordMailTransfer->getRecipients()->count());
        /** @var \Generated\Shared\Transfer\MailRecipientTransfer $mailRecipientTransfer */
        $mailRecipientTransfer = $resetPasswordMailTransfer->getRecipients()[0];
        $this->assertSame(static::EMAIL, $mailRecipientTransfer->getEmail());
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGenerator
     */
    protected function createMailTransferGenerator(): MailTransferGenerator
    {
        return new MailTransferGenerator(
            $this->createAuthMailConnectorConfigMock()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAuthMailConnectorConfigMock(): MockObject
    {
        $authMailConnectorConfigMockBuilder = $this->getMockBuilder(AuthMailConnectorConfig::class)
            ->setMethods(['getBaseUrlZed']);

        $authMailConnectorConfigMock = $authMailConnectorConfigMockBuilder->getMock();
        $authMailConnectorConfigMock
            ->method('getBaseUrlZed')
            ->willReturn(static::BASE_URL_ZED);

        return $authMailConnectorConfigMock;
    }
}
