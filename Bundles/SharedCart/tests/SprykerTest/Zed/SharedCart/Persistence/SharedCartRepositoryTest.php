<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Persistence
 * @group SharedCartRepositoryTest
 * Add your own group annotations below this line
 */
class SharedCartRepositoryTest extends Unit
{
    protected const PERMISSION_KEY = 'PERMISSION_KEY';

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartPersistenceTester
     */
    protected $tester;

    /**
     * @group foo
     * @return void
     */
    public function testFindPermissionsByCustomerShouldReturnPermissionsWithQuotes(): void
    {
        // Arrange
        $sharedCartRepository = new SharedCartRepository();

        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer1 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $quoteTransfer2 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $this->tester->haveQuotePermissionGroup(static::PERMISSION_KEY, [
            static::PERMISSION_KEY,
        ]);

        // Act
        $customerPermissions = $sharedCartRepository->findPermissionsByCustomer($customerTransfer->getCustomerReference())->getPermissions();

        // Assert
        $this->assertNotEmpty($customerPermissions);

        $permissionKeys = [];
        foreach ($customerPermissions as $permission) {
            $permissionKeys[] = $permission->getKey();
            $configuration = $permission->getConfiguration();
            $quoteIds = array_map(
                'intval',
                $configuration[SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION] ?? []
            );

            $this->assertNotEmpty($quoteIds);
            $this->assertContains($quoteTransfer1->getIdQuote(), $quoteIds);
            $this->assertContains($quoteTransfer2->getIdQuote(), $quoteIds);
        }

        $this->assertContains(static::PERMISSION_KEY, $permissionKeys);
    }
}
