<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ApiKey;

use Codeception\Actor;
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
