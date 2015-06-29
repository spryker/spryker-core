<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKey;

interface KeyManagerInterface
{
    /**
     * @param string $keyName
     *
     * @return int
     * @throws KeyExistsException
     * @throws \Exception
     * @throws PropelException
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
     * @return SpyGlossaryKey
     * @throws MissingKeyException
     */
    public function getKey($keyName);

    /**
     * @param string $currentKeyName
     * @param string $newKeyName
     *
     * @return bool
     * @throws MissingKeyException
     */
    public function updateKey($currentKeyName, $newKeyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName);

    public function synchronizeKeys();
}
