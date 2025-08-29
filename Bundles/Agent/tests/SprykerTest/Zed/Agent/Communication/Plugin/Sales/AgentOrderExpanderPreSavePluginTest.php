<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Agent\AgentConfig;
use Spryker\Zed\Agent\Communication\Plugin\Sales\AgentOrderExpanderPreSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Agent
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group AgentOrderExpanderPreSavePluginTest
 * Add your own group annotations below this line
 */
class AgentOrderExpanderPreSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const AGENT_EMAIL = 'agent@spryker.com';

    /**
     * @var \SprykerTest\Zed\Agent\AgentCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandDoesNotAddAgentWhenFeatureIsDisabled(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMockedConfig(false);

        // Act
        $salesOrderEntityTransfer = $plugin->expand(new SpySalesOrderEntityTransfer(), new QuoteTransfer());

        // Assert
        $this->assertNull($salesOrderEntityTransfer->getAgentEmail());
    }

    /**
     * @return void
     */
    public function testExpandDoesNotAddAgentWhenFeatureIsEnabledButAgentEmailNotInQuote(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMockedConfig(true);

        // Act
        $salesOrderEntityTransfer = $plugin->expand(new SpySalesOrderEntityTransfer(), new QuoteTransfer());

        // Assert
        $this->assertNull($salesOrderEntityTransfer->getAgentEmail());
    }

    /**
     * @return void
     */
    public function testExpandAddsAgentEmailWhenFeatureIsEnabledAndAgentEmailExistsInQuote(): void
    {
        // Arrange
        $plugin = $this->createPluginWithMockedConfig(true);
        $quoteTransfer = (new QuoteTransfer())->setAgentEmail(static::AGENT_EMAIL);

        // Act
        $salesOrderEntityTransfer = $plugin->expand(new SpySalesOrderEntityTransfer(), $quoteTransfer);

        // Assert
        $this->assertSame(static::AGENT_EMAIL, $salesOrderEntityTransfer->getAgentEmail());
    }

    /**
     * @param bool $isSalesOrderAgentEnabled
     *
     * @return \Spryker\Zed\Agent\Communication\Plugin\Sales\AgentOrderExpanderPreSavePlugin
     */
    protected function createPluginWithMockedConfig(bool $isSalesOrderAgentEnabled): AgentOrderExpanderPreSavePlugin
    {
        $configMock = $this->createMock(AgentConfig::class);
        $configMock->method('isSalesOrderAgentEnabled')
            ->willReturn($isSalesOrderAgentEnabled);

        $plugin = new AgentOrderExpanderPreSavePlugin();
        $plugin->setConfig($configMock);

        return $plugin;
    }
}
