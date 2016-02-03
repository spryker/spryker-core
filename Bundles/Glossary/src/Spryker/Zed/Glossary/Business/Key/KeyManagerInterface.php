<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Business\Key;

interface KeyManagerInterface
{

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return int
     */
    public function createKey($keyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKey
     */
    public function getKey($keyName);

    /**
     * @param string $currentKeyName
     * @param string $newKeyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return bool
     */
    public function updateKey($currentKeyName, $newKeyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName);

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return int
     */
    public function getOrCreateKey($keyName);

}
