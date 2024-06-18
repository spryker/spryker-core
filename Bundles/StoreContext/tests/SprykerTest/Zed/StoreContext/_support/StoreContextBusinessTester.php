<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\StoreContext;

use Codeception\Actor;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Business\StoreContextFacade;
use Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class StoreContextBusinessTester extends Actor
{
    use _generated\StoreContextBusinessTesterActions;

    /**
     * @var string
     */
    public const STORE_NAME_XX = 'XX';

    /**
     * @var string
     */
    public const FIELD_STORE_NAME = 'name';

    /**
     * @var string
     */
    public const MESSAGE_APP_NOT_VALID = 'Application %application% is not valid.';

    /**
     * @var string
     */
    public const MESSAGE_DEFAULT_CONTEXT_NOT_EXIST = 'Default store context do not exist in the store settings collection.';

    /**
     * @var string
     */
    public const MESSAGE_STORE_CONTEXT_DOESNT_EXIST = 'Store context not found for store id: %id%.';

    /**
     * @var string
     */
    public const MESSAGE_STORE_CONTEXT_EXISTS = 'Store context already exist for id: %id%.';

    /**
     * @var string
     */
    public const MESSAGE_STORE_CONTEXT_MISSING = 'Store context collection is missing.';

    /**
     * @var string
     */
    public const TIMEZONE_DEFAULT = 'Europe/Berlin';

    /**
     * @var string
     */
    public const TIMEZONE_ZED = 'Europe/London';

    /**
     * @var string
     */
    public const APP_NAME = 'ZED';

    /**
     * @var string
     */
    public const APP_NAME_YVES = 'YVES';

    /**
     * @var string
     */
    public const APPLICATION_ZED = 'ZED';

    /**
     * @var string
     */
    public const TIMEZONE_LONDON = 'Europe/London';

    /**
     * @var string
     */
    public const TIMEZONE_BERLIN = 'Europe/Berlin';

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreContextTransfer
     */
    public function haveStoreContext(int $idStore): StoreContextTransfer
    {
        $storeContextCollectionRequestTransfer = (new StoreContextCollectionRequestTransfer())
            ->addContext(
                (new StoreContextTransfer())
                    ->setStore((new StoreTransfer())->setIdStore($idStore))
                    ->setApplicationContextCollection($this->createDefaultStoreApplicationContextCollectionTransfer()),
            );

        $storeContextCollectionResponseTransfer = $this->getStoreContextFacade()->createStoreContextCollection(
            $storeContextCollectionRequestTransfer,
        );

        return $storeContextCollectionResponseTransfer->getContexts()->offsetGet(0);
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface
     */
    public function createStoreContextFacade(): StoreContextFacadeInterface
    {
        return new StoreContextFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreContextCollectionTransfer
     */
    public function createStoreContextCollectionTransfer(): StoreContextCollectionTransfer
    {
        return new StoreContextCollectionTransfer();
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface
     */
    protected function getStoreContextFacade(): StoreContextFacadeInterface
    {
        return $this->getLocator()->storeContext()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer
     */
    protected function createDefaultStoreApplicationContextCollectionTransfer(): StoreApplicationContextCollectionTransfer
    {
        return (new StoreApplicationContextCollectionTransfer())
            ->addApplicationContext(
                (new StoreApplicationContextTransfer())
                    ->setTimezone(static::TIMEZONE_BERLIN),
            )->addApplicationContext(
                (new StoreApplicationContextTransfer())
                    ->setTimezone(static::TIMEZONE_LONDON)
                    ->setApplication(static::APPLICATION_ZED),
            );
    }
}
