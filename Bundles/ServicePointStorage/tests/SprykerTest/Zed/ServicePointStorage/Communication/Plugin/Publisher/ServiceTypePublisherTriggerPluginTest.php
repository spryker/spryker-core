<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Spryker\Zed\ServicePointStorage\Communication\Plugin\Publisher\ServiceTypePublisherTriggerPlugin;
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
 * @group ServiceTypePublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class ServiceTypePublisherTriggerPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageCommunicationTester
     */
    protected ServicePointStorageCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServiceTypeTableIsEmpty();
    }

    /**
     * @dataProvider getServiceTypePublisherGetDataProvider
     *
     * @param int $serviceTypesNumberInDb
     * @param int $offset
     * @param int $limit
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetDataShouldReturnDataByOffsetAndLimit(
        int $serviceTypesNumberInDb,
        int $offset,
        int $limit,
        int $expectedCount
    ): void {
        // Arrange
        for ($i = 1; $i <= $serviceTypesNumberInDb; $i++) {
            $this->tester->haveServiceType();
        }

        // Act
        $serviceTypeTransfers = (new ServiceTypePublisherTriggerPlugin())->getData($offset, $limit);

        // Assert
        $this->assertCount($expectedCount, $serviceTypeTransfers);
    }

    /**
     * @return array<string, list<int>>
     */
    protected function getServiceTypePublisherGetDataProvider(): array
    {
        return [
            'Should return empty collection when service types do not exist' => [
                0, 0, 1, 0,
            ],
            'Should return empty collection when 0 limit is provided' => [
                1, 0, 0, 0,
            ],
            'Should return empty collection when offset is higher then number of service types' => [
                1, 2, 1, 0,
            ],
            'Should return empty collection when offset equals the number of service types' => [
                1, 1, 1, 0,
            ],
            'Should return collection when correct limit and 0 offset are provided' => [
                2, 0, 1, 1,
            ],
            'Should return collection when both correct limit and offset are provided' => [
                2, 1, 1, 1,
            ],
        ];
    }
}
