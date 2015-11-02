<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;

interface KeyManagerInterface
{

    /**
     * @param string $keyName
     *
     * @throws KeyExistsException
     * @throws \Exception
     * @throws PropelException
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
     * @throws MissingKeyException
     *
     * @return SpyGlossaryKey
     */
    public function getKey($keyName);

    /**
     * @param string $currentKeyName
     * @param string $newKeyName
     *
     * @throws MissingKeyException
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

}
