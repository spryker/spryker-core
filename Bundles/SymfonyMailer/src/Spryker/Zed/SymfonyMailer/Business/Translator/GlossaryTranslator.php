<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Business\Translator;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToGlossaryFacadeInterface;

class GlossaryTranslator implements TranslatorInterface
{
    /**
     * @var \Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(SymfonyMailerToGlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param string $keyName
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public function translate(MailTransfer $mailTransfer, string $keyName, array $data = []): string
    {
        if (!$this->glossaryFacade->hasTranslation($keyName, $mailTransfer->getLocale())) {
            return $keyName;
        }

        return $this->glossaryFacade->translate($keyName, $data, $mailTransfer->getLocale());
    }
}
