<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Dependency\Facade;

use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;

class PayolutionToGlossaryBridge implements PayolutionToGlossaryInterface
{

    /**
     * @var GlossaryFacade
     */
    protected $glossaryFacade;

    /**
     * @param GlossaryFacade $glossaryFacade
     */
    public function __construct($glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param string $keyName
     * @param LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $locale = null)
    {
        return $this->glossaryFacade->hasTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @throws MissingTranslationException
     *
     * @return string
     */
    public function translate($keyName, array $data = [])
    {
        return $this->glossaryFacade->translate($keyName, $data);
    }

}
