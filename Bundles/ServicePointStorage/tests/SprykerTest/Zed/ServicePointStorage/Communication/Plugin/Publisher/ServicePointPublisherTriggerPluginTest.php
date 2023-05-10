<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\ServicePointStorage\Communication\Plugin\Publisher\ServicePointPublisherTriggerPlugin;
use SprykerTest\Zed\ServicePointStorage\ServicePointStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ServicePointPublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class ServicePointPublisherTriggerPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID = 'SERVICE_POINT_UUID';

    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageCommunicationTester
     */
    protected ServicePointStorageCommunicationTester $tester;

    /**
     * @dataProvider getServicePointPublisherGetDataProvider
     *
     * @param list<array<string, mixed>> $servicePointsData
     * @param int $offset
     * @param int $limit
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetDataShouldReturnDataByOffsetAndLimit(
        array $servicePointsData,
        int $offset,
        int $limit,
        int $expectedCount
    ): void {
        // Arrange
        foreach ($servicePointsData as $servicePointData) {
            $this->tester->createServicePointTransferWithStoreRelations($servicePointData);
        }

        // Act
        $data = (new ServicePointPublisherTriggerPlugin())->getData($offset, $limit);

        // Assert
        $this->assertCount($expectedCount, $data);
    }

    /**
     * @return array<string, array<list<array<string, mixed>>|int>>
     */
    protected function getServicePointPublisherGetDataProvider(): array
    {
        return [
            'Should return empty data when 0 limit is provided' => [
                [], 0, 0, 0,
            ],
            'Should return data when correct limit and offset provided' => [
                [[ServicePointTransfer::UUID => static::SERVICE_POINT_UUID]], 0, 1, 1,
            ],
        ];
    }
}
