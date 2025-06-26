<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContext\Communication\Plugin\Store;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Communication\Plugin\Store\TimezoneStoreCollectionExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContext
 * @group Communication
 * @group Plugin
 * @group Store
 * @group TimezoneStoreCollectionExpanderPluginTest
 * Add your own group annotations below this line
 */
class TimezoneStoreCollectionExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TIMEZONE_EUROPE_BERLIN = 'Europe/Berlin';

    /**
     * @var string
     */
    protected const TIMEZONE_ASIA_TOKYO = 'Asia/Tokyo';

    /**
     * @return void
     */
    public function testShouldExpandStoresWithTimezonesFromDefaultApplicationContext(): void
    {
        // Arrange
        $storeTransfers = [
            $this->expandStoreTransferWithApplicationContext(new StoreTransfer()),
            $this->expandStoreTransferWithApplicationContext(new StoreTransfer()),
        ];

        // Act
        $storeTransfers = (new TimezoneStoreCollectionExpanderPlugin())->expand($storeTransfers);

        // Assert
        $this->assertSame(static::TIMEZONE_EUROPE_BERLIN, $storeTransfers[0]->getTimezone());
        $this->assertSame(static::TIMEZONE_EUROPE_BERLIN, $storeTransfers[1]->getTimezone());
    }

    /**
     * @return void
     */
    public function testShouldExpandStoresWithTimezonesFromZedApplicationContext(): void
    {
        // Arrange
        $storeTransfers = [
            $this->expandStoreTransferWithApplicationContext(new StoreTransfer(), 'ZED'),
        ];

        // Act
        $storeTransfers = (new TimezoneStoreCollectionExpanderPlugin())->expand($storeTransfers);

        // Assert
        $this->assertSame(static::TIMEZONE_EUROPE_BERLIN, $storeTransfers[0]->getTimezone());
    }

    /**
     * @return void
     */
    public function testShouldNotExpandStoresWithTimezones(): void
    {
        // Arrange
        $storeTransfers = [
            new StoreTransfer(),
        ];

        // Act
        $storeTransfers = (new TimezoneStoreCollectionExpanderPlugin())->expand($storeTransfers);

        // Assert
        $this->assertNotSame(static::TIMEZONE_EUROPE_BERLIN, $storeTransfers[0]->getTimezone());
    }

    /**
     * @return void
     */
    public function testShouldExpandStoresWithTimezonesFromDefaultAndZedApplicationContext(): void
    {
        // Arrange
        $storeTransfer = $this->expandStoreTransferWithApplicationContext(new StoreTransfer(), 'ZED', static::TIMEZONE_ASIA_TOKYO);
        $storeTransfer = $this->expandStoreTransferWithApplicationContext($storeTransfer);

        // Act
        $storeTransfers = (new TimezoneStoreCollectionExpanderPlugin())->expand([$storeTransfer]);

        // Assert
        $this->assertSame(static::TIMEZONE_ASIA_TOKYO, $storeTransfers[0]->getTimezone());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string|null $application
     * @param string|null $timezone
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function expandStoreTransferWithApplicationContext(
        StoreTransfer $storeTransfer,
        ?string $application = null,
        ?string $timezone = null
    ): StoreTransfer {
        $storeApplicationContextTransfer = (new StoreApplicationContextTransfer())
            ->setTimezone($timezone ?? static::TIMEZONE_EUROPE_BERLIN)
            ->setApplication($application);

        $storeApplicationContextCollectionTransfer = $storeTransfer->getApplicationContextCollection() ?? new StoreApplicationContextCollectionTransfer();
        $storeApplicationContextCollectionTransfer->addApplicationContext($storeApplicationContextTransfer);

        return $storeTransfer->setApplicationContextCollection($storeApplicationContextCollectionTransfer);
    }
}
