<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantAgent\Communication\Plugin\User;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\MerchantAgent\Communication\Plugin\User\MerchantAgentUserQueryCriteriaExpanderPlugin;
use SprykerTest\Zed\MerchantAgent\MerchantAgentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantAgent
 * @group Communication
 * @group Plugin
 * @group User
 * @group MerchantAgentUserQueryCriteriaExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantAgentUserQueryCriteriaExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantAgent\MerchantAgentCommunicationTester
     */
    protected MerchantAgentCommunicationTester $tester;

    /**
     * @dataProvider getExpandDataProvider
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $expectedQueryCriteriaTransfer
     *
     * @return void
     */
    public function testExpand(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        UserCriteriaTransfer $userCriteriaTransfer,
        QueryCriteriaTransfer $expectedQueryCriteriaTransfer
    ): void {
        // Arrange
        $merchantAgentUserQueryCriteriaExpanderPlugin = new MerchantAgentUserQueryCriteriaExpanderPlugin();

        // Act
        $queryCriteriaTransfer = $merchantAgentUserQueryCriteriaExpanderPlugin->expand(
            $queryCriteriaTransfer,
            $userCriteriaTransfer,
        );

        // Assert
        $this->assertSame($expectedQueryCriteriaTransfer->getConditions(), $queryCriteriaTransfer->getConditions());
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\UserCriteriaTransfer|\Generated\Shared\Transfer\QueryCriteriaTransfer>>
     */
    protected function getExpandDataProvider(): array
    {
        return [
            'Should not to expand query criteria when `UserCriteriaTransfer` does not have conditions.' => [
                new QueryCriteriaTransfer(),
                new UserCriteriaTransfer(),
                new QueryCriteriaTransfer(),
            ],
            'Should not to expand query criteria when `isMerchantAgent` is null.' => [
                new QueryCriteriaTransfer(),
                (new UserCriteriaTransfer())->setUserConditions(new UserConditionsTransfer()),
                new QueryCriteriaTransfer(),
            ],
            'Should expand query criteria with `isMerchantAgent` condition and not remove previously added conditions.' => [
                (new QueryCriteriaTransfer())->setConditions([
                    'spy_user.id_user = ?' => 888,
                ]),
                (new UserCriteriaTransfer())->setUserConditions(
                    (new UserConditionsTransfer())->setIsMerchantAgent(true),
                ),
                (new QueryCriteriaTransfer())->setConditions([
                    'spy_user.id_user = ?' => 888,
                    'spy_user.is_merchant_agent = ?' => true,
                ]),
            ],
        ];
    }
}
