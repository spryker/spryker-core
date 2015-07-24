<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKey;

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
     * @param array $data
     *
     * @throws MissingKeyException
     */
    protected function checkKeyDoesExist($data)
    {
        $key = $this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_KEY);
        $name = isset($data[$key]) ? $data[$key] : false;

        if (!empty($name) && !$this->hasKey($name)) {
            throw new MissingKeyException(
                sprintf(
                    'Tried to update key %s, but it does not exist',
                    $name
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
     * @return SpyGlossaryKey
     * @throws MissingKeyException
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
     * @param array $data
     *
     * @return int
     * @throws KeyExistsException
     * @throws PropelException
     */
    public function createKey($data)
    {
        $this->checkKeyDoesNotExist($data);

        $key = new SpyGlossaryKey();
        $key->setNew(true);

        $key->setKey($data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_KEY)]);
        $key->setIsActive(true === $data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_IS_ACTIVE)] ? 1 : 0);

        $key->save();

        return $key->getPrimaryKey();
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws MissingKeyException
     */
    public function updateKey($data)
    {
        $this->checkKeyDoesExist($data);

        $key = new SpyGlossaryKey();
        $key->setNew(false);

        $key->setIdGlossaryKey($data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY)]);
        $key->setKey($data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_KEY)]);
        $key->setIsActive(true === $data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_IS_ACTIVE)] ? 1 : 0);

        $key->save();

        return true;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function cutTablePrefix($key)
    {
        $position = mb_strrpos($key, '.');

        return (false !== $position) ? mb_substr($key, $position + 1) : $key;
    }

    /**
     * @param array $data
     *
     * @throws KeyExistsException
     */
    protected function checkKeyDoesNotExist($data)
    {
        $key = $this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_KEY);
        $name = isset($data[$key]) ? $data[$key] : false;

        if (!empty($name) && $this->hasKey($name)) {
            throw new KeyExistsException(
                sprintf(
                    'Tried to create key %s, but it already exists',
                    $name
                )
            );
        }
    }
}
