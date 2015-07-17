<?php

namespace SprykerFeature\Zed\GlossaryQueue\Business\Model;

use Generated\Shared\Queue\QueueMessageInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\GlossaryQueue\Dependency\Facade\GlossaryQueueToGlossaryInterface;

class QueueTranslationManager implements QueueTranslationManagerInterface
{

    /**
     * @var GlossaryQueueToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param GlossaryQueueToGlossaryInterface $glossaryFacade
     */
    public function __construct(GlossaryQueueToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param QueueMessageInterface $queueMessage
     */
    public function processTranslationMessage(QueueMessageInterface $queueMessage)
    {
        $translation = $queueMessage->getPayload();

        $translationKey = $translation['translation_key'];
        $translationValue = $translation['translation_value'];
        $translationIsActive = $translation['translation_is_active'];
        $localeName = $translation['translation_locale'];

        if (!$this->glossaryFacade->hasKey($translationKey)) {
            $this->glossaryFacade->createKey($translationKey);
        }

        $localeDto = new LocaleTransfer();
        $localeDto->setLocaleName($localeName);

        if (!$this->glossaryFacade->hasTranslation($translationKey, $localeDto)) {
            $this->glossaryFacade->createAndTouchTranslation(
                $translationKey,
                $localeDto,
                $translationValue,
                $translationIsActive
            );
        } else {
            $this->glossaryFacade->updateAndTouchTranslation(
                $translationKey,
                $localeDto,
                $translationValue,
                $translationIsActive
            );
        }
    }

}
