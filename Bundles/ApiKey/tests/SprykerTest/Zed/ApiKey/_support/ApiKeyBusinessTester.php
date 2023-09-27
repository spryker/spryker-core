<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ApiKey;

use Codeception\Actor;
use DateTime;
use Orm\Zed\ApiKey\Persistence\Base\SpyApiKeyQuery;
use Orm\Zed\ApiKey\Persistence\Map\SpyApiKeyTableMap;
use Orm\Zed\ApiKey\Persistence\SpyApiKey;
use Propel\Runtime\ActiveQuery\Criteria;

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
 * @method \Spryker\Zed\ApiKey\Business\ApiKeyFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ApiKey\PHPMD)
 */
class ApiKeyBusinessTester extends Actor
{
    use _generated\ApiKeyBusinessTesterActions;

    /**
     * @var string
     */
    public const FOO_NAME = 'Foo';

    /**
     * @var string
     */
    public const BAR_NAME = 'Bar';

    /**
     * @var string
     */
    public const FOO_KEY = 'Key';

    /**
     * @var string
     */
    public const FOO_KEY_HASH = 'foo';

    /**
     * @var string
     */
    public const EXPIRED_KEY_NAME = 'EXPIRED';

    /**
     * @var string
     */
    public const NO_EXPIRED_KEY_NAME = 'NO_EXPIRED';

    /**
     * @var string
     */
    public const EXPIRED_API_KEY = 'expiredtestkey';

    /**
     * @var string
     */
    public const NO_EXPIRED_API_KEY = 'noexpiredtestkey';

    /**
     * @var string
     */
    protected const EXPIRED_API_KEY_HASH = '85ed2a56f82aed1644372cf7ff8cc8e9b1f3d46f404af9c2a73fe1828855138a';

    /**
     * @var string
     */
    protected const NO_EXPIRED_API_KEY_HASH = 'fe4f0f707a786f2b4b5977cb49b2348d0fcb40dd5840a0ea4332c4af337e0c35';

    /**
     * @return void
     */
    public function createFakeApiKeyRecord(): void
    {
        (new SpyApiKey())->setName(static::FOO_NAME)
            ->setKeyHash(static::FOO_KEY_HASH)
            ->setCreatedBy(1)
            ->save();
    }

    /**
     * @return void
     */
    public function createFakeApiKeyExpiredRecord(): void
    {
        (new SpyApiKey())->setName(static::EXPIRED_KEY_NAME)
            ->setKeyHash(static::EXPIRED_API_KEY_HASH)
            ->setValidTo((new DateTime('-2 day')))
            ->setCreatedBy(1)
            ->save();
    }

    /**
     * @return void
     */
    public function createFakeApiKeyNotExpiredRecord(): void
    {
        (new SpyApiKey())->setName(static::NO_EXPIRED_KEY_NAME)
            ->setKeyHash(static::NO_EXPIRED_API_KEY_HASH)
            ->setValidTo((new DateTime('+2 day')))
            ->setCreatedBy(1)
            ->save();
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\ApiKey\Persistence\SpyApiKey
     */
    public function getFakeApiKeyEntityByName(string $name): SpyApiKey
    {
        return SpyApiKeyQuery::create()
            ->filterByName($name)
            ->findOne();
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\ApiKey\Persistence\SpyApiKey|null
     */
    public function getFakeApiKeyEntityById(int $id): ?SpyApiKey
    {
        return SpyApiKeyQuery::create()
            ->filterByIdApiKey($id)
            ->findOne();
    }

    /**
     * @return int
     */
    public function getNonExistingId(): int
    {
        $lastID = SpyApiKeyQuery::create()
            ->orderBy(SpyApiKeyTableMap::COL_ID_API_KEY, Criteria::DESC)
            ->findOne()
            ->getIdApiKey();

        return $lastID + 1;
    }
}
