<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Agent\Plugin\Quote;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Agent\AgentClient;
use Spryker\Client\Agent\AgentConfig;
use Spryker\Client\Agent\AgentFactory;
use Spryker\Client\Agent\Plugin\Quote\AgentQuoteTransferExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Agent
 * @group Plugin
 * @group Quote
 * @group AgentQuoteTransferExpanderPluginTest
 * Add your own group annotations below this line
 */
class AgentQuoteTransferExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const AGENT_USERNAME = 'agent@spryker.com';

    /**
     * @var \SprykerTest\Client\Agent\AgentClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandQuoteDoesNotAddAgentWhenFeatureIsDisabled(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMockedDependencies(false, false);

        // Act
        $expandedQuoteTransfer = $plugin->expandQuote(new QuoteTransfer());

        // Assert
        $this->assertNull($expandedQuoteTransfer->getAgentEmail());
    }

    /**
     * @return void
     */
    public function testExpandQuoteDoesNotAddAgentWhenFeatureIsEnabledButAgentIsNotLoggedIn(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMockedDependencies(true, false);

        // Act
        $expandedQuoteTransfer = $plugin->expandQuote(new QuoteTransfer());

        // Assert
        $this->assertNull($expandedQuoteTransfer->getAgentEmail());
    }

    /**
     * @return void
     */
    public function testExpandQuoteAddsAgentEmailWhenFeatureIsEnabledAndAgentIsLoggedIn(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMockedDependencies(true, true);

        // Act
        $expandedQuoteTransfer = $plugin->expandQuote(new QuoteTransfer());

        // Assert
        $this->assertSame(static::AGENT_USERNAME, $expandedQuoteTransfer->getAgentEmail());
    }

    /**
     * @param bool $isSalesOrderAgentEnabled
     * @param bool $isAgentLoggedIn
     *
     * @return \Spryker\Client\Agent\Plugin\Quote\AgentQuoteTransferExpanderPlugin
     */
    protected function createPluginWithMockedDependencies(bool $isSalesOrderAgentEnabled, bool $isAgentLoggedIn): AgentQuoteTransferExpanderPlugin
    {
        // Mock config
        $configMock = $this->createMock(AgentConfig::class);
        $configMock->method('isSalesOrderAgentEnabled')
            ->willReturn($isSalesOrderAgentEnabled);

        // Mock factory
        $factoryMock = $this->createMock(AgentFactory::class);
        $factoryMock->method('getConfig')
            ->willReturn($configMock);

        // Mock client
        $clientMock = $this->createMock(AgentClient::class);
        $clientMock->method('isLoggedIn')
            ->willReturn($isAgentLoggedIn);

        if ($isAgentLoggedIn) {
            $userTransfer = (new UserTransfer())->setUsername(static::AGENT_USERNAME);
            $clientMock->method('getAgent')
                ->willReturn($userTransfer);
        }

        // Set up plugin with mocks
        $plugin = new AgentQuoteTransferExpanderPlugin();
        $plugin->setFactory($factoryMock);
        $plugin->setClient($clientMock);

        return $plugin;
    }
}
