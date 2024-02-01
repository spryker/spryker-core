<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\Locale;

use Codeception\Actor;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Glue\Locale\Negotiator\LanguageNegotiator;
use Spryker\Glue\Locale\Negotiator\LanguageNegotiatorInterface;
use Spryker\Service\Locale\LocaleServiceInterface;

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
 *
 * @SuppressWarnings(\SprykerTest\Glue\Locale\PHPMD)
 */
class LocaleGlueTester extends Actor
{
    use _generated\LocaleGlueTesterActions;

    /**
     * @var string
     */
    public const DE_LOCALE = 'de_DE';

    /**
     * @var string
     */
    public const EN_LOCALE = 'en_US';

    /**
     * @var string
     */
    public const DE_STORE_NAME = 'DE';

    /**
     * @var string
     */
    public const DE_ACCEPT_LANGUAGE_HEADER = 'de-DE, de;q=0.8';

    /**
     * @var array<string, string>
     */
    public const DEFAULT_DE_STORE_LOCALES = [
        'de' => 'de_DE',
        'en' => 'en_US',
    ];

    /**
     * @var string
     */
    public const EXCEPTION_MESSAGE = 'Unable to get locale codes by current store.';

    /**
     * @param \Spryker\Client\Locale\LocaleClientInterface $localeClientMock
     * @param \Spryker\Service\Locale\LocaleServiceInterface $localeServiceMock
     * @param \Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface $storeClientMock
     *
     * @return \Spryker\Glue\Locale\Negotiator\LanguageNegotiatorInterface
     */
    public function createLanguageNegotiator(
        LocaleClientInterface $localeClientMock,
        LocaleServiceInterface $localeServiceMock,
        LocaleToStoreClientInterface $storeClientMock
    ): LanguageNegotiatorInterface {
        return new LanguageNegotiator($localeClientMock, $localeServiceMock, $storeClientMock);
    }

    /**
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }
}
