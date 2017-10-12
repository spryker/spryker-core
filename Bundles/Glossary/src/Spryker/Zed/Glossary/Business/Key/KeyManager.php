<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business\Key;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Spryker\Zed\Glossary\Business\Exception\KeyExistsException;
use Spryker\Zed\Glossary\Business\Exception\MissingKeyException;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

class KeyManager implements KeyManagerInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface $queryContainer
     */
    public function __construct(GlossaryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $currentKeyName
     * @param string $newKeyName
     *
     * @return bool
     */
    public function updateKey($currentKeyName, $newKeyName)
    {
        $this->checkKeyDoesExist($currentKeyName);

        $key = $this->getKey($currentKeyName);
        $key->setKey($newKeyName);

        $key->save();

        return true;
    }

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return void
     */
    protected function checkKeyDoesExist($keyName)
    {
        if (!$this->hasKey($keyName)) {
            throw new MissingKeyException(
                sprintf(
                    'Tried to update key %s, but it does not exist',
                    $keyName
                )
            );
        }
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        $keyQuery = $this->queryContainer->queryKey($keyName);

        return $keyQuery->count() > 0;
    }

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKey
     */
    public function getKey($keyName)
    {
        $key = $this->queryContainer
            ->queryKey($keyName)
            ->findOne();

        if (!$key) {
            throw new MissingKeyException('Tried to retrieve a missing glossary key');
        }

        return $key;
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName)
    {
        $keyQuery = $this->queryContainer->queryKey($keyName);
        $entity = $keyQuery->findOne();
        if (!$entity) {
            return true;
        }
        $entity->setIsActive(false);
        $entity->save();

        return true;
    }

    /**
     * @param array $idKeys
     *
     * @return void
     */
    public function deleteKeys(array $idKeys)
    {
        $this->queryContainer
            ->queryGlossaryKeyByIdGlossaryKeys($idKeys)
            ->delete();
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey($keyName)
    {
        $this->checkKeyDoesNotExist($keyName);

        $keyEntity = new SpyGlossaryKey();
        $keyEntity->setKey($keyName);
        $keyEntity->save();

        return $keyEntity->getPrimaryKey();
    }

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     *
     * @return void
     */
    protected function checkKeyDoesNotExist($keyName)
    {
        if ($this->hasKey($keyName)) {
            throw new KeyExistsException(
                sprintf(
                    'Tried to create key %s, but it already exists',
                    $keyName
                )
            );
        }
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey($keyName)
    {
        if ($this->hasKey($keyName)) {
            return $this->getKey($keyName)->getPrimaryKey();
        }

        return $this->createKey($keyName);
    }

    /**
     * @param string $keyFragment
     *
     * @return array
     */
    public function getKeySuggestions($keyFragment)
    {
        return $this
            ->queryContainer
            ->queryActiveKeysByName('%' . $keyFragment . '%')
            ->select([SpyGlossaryKeyTableMap::COL_KEY])
            ->find()
            ->toArray();
    }
}
