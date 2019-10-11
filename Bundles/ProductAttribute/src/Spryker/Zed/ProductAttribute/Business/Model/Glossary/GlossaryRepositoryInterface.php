<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Glossary;

use Generated\Shared\Transfer\LocaleTransfer;

interface GlossaryRepositoryInterface
{
    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function getTranslationByKeyNameAndLocaleTransfer($keyName, LocaleTransfer $locale): string;

    /**
     * @param string[] $keyNames
     * @param string[] $localeNames
     *
     * @return void
     */
    public function loadTranslations(array $keyNames, array $localeNames): void;
}
