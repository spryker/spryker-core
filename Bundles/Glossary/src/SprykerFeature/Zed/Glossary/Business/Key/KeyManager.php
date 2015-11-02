<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;

class KeyManager implements KeyManagerInterface
{

    /**
     * @var GlossaryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param GlossaryQueryContainerInterface $queryContainer
     */
    public function __construct(GlossaryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $currentKeyName
     * @param string $newKeyName
     *
     * @throws MissingKeyException
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
     * @throws MissingKeyException
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
     * @throws MissingKeyException
     *
     * @return SpyGlossaryKey
     */
    public function getKey($keyName)
    {
        $key = $this->queryContainer
            ->queryKey($keyName)
            ->findOne()
        ;

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
     * @param string $keyName
     *
     * @throws KeyExistsException
     * @throws PropelException
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
     * @throws KeyExistsException
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

}
