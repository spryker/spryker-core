<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Agent;

use Codeception\Test\Unit;
use Spryker\Shared\Agent\AgentConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Agent
 * @group AgentClientTest
 * Add your own group annotations below this line
 */
class AgentClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Agent\AgentClientTester
     */
    protected $tester;

    /**
     * @dataProvider applyAgentAccessOnSecuredPatternDataProvider
     *
     * @param array $agentAllowedSecuredPatternList
     * @param string $securedPattern
     * @param string $expectedResult
     *
     * @return void
     */
    public function testApplyAgentAccessOnSecuredPatternWillModifySecuredPatternToAllowAgentAccess(
        array $agentAllowedSecuredPatternList,
        string $securedPattern,
        string $expectedResult
    ): void {
        // Arrange
        $this->tester->setConfig(AgentConstants::AGENT_ALLOWED_SECURED_PATTERN_LIST, $agentAllowedSecuredPatternList);

        // Act
        $modifiedSecuredPattern = $this->tester->getClient()->applyAgentAccessOnSecuredPattern($securedPattern);

        // Assert
        $this->assertSame($expectedResult, $modifiedSecuredPattern);
    }

    /**
     * @return array
     */
    public function applyAgentAccessOnSecuredPatternDataProvider(): array
    {
        return [
            [
                [
                    '|^(/en|/de)?/cart(?!/add)($|/)',
                ],
                '(^/login_check$|^(/en|/de)?/customer($|/)|^(/en|/de)?/wishlist($|/)|^(/en|/de)?/shopping-list($|/)|^(/en|/de)?/quote-request($|/)|^(/en|/de)?/comment($|/)|^(/en|/de)?/company(?!/register)($|/)|^(/en|/de)?/multi-cart($|/)|^(/en|/de)?/shared-cart($|/)|^(/en|/de)?/cart(?!/add)($|/)|^(/en|/de)?/checkout($|/))',
                '(^/login_check$|^(/en|/de)?/customer($|/)|^(/en|/de)?/wishlist($|/)|^(/en|/de)?/shopping-list($|/)|^(/en|/de)?/quote-request($|/)|^(/en|/de)?/comment($|/)|^(/en|/de)?/company(?!/register)($|/)|^(/en|/de)?/multi-cart($|/)|^(/en|/de)?/shared-cart($|/)|^(/en|/de)?/checkout($|/))',
            ],
            [
                [
                    'wishlist',
                    '|/de',
                ],
                '^(/en|/de)?/wishlist($|/)|^(/en|/de)?/shopping-list($|/)',
                '^(/en)?/($|/)|^(/en)?/shopping-list($|/)',
            ],
            [
                [
                    'test',
                ],
                '^(/en|/de)?/customer',
                '^(/en|/de)?/customer',
            ],
        ];
    }
}
