<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface MessengerToGlossaryInterface
{

    /**
     * @param string $keyName
     * @param LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $locale = null);

    /**
     * @param string $keyName
     * @param array $data
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translate($keyName, array $data = []);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

}
