<?php

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class KeyForm extends AbstractForm
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     * @param QueryContainerInterface $queryContainerInterface
     * @param GlossaryToLocaleInterface $localFacade
     */
    public function __construct(
        Request $request,
        QueryContainerInterface $queryContainerInterface,
        GlossaryToLocaleInterface $localFacade
    ) {
        parent::__construct($request, $queryContainerInterface);
        $this->request = $request;
        $this->localeFacade = $localFacade;
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [];
    }

    public function addFormFields()
    {
        $fields = [];
        $fields[] = $this->addField('key')
            ->setRefresh(false)
        ;

        $locales = $this->localeFacade->getAvailableLocales();

        foreach ($locales as $localeId => $locale) {
            $form = new TranslationForm(
                $this->request,
                $this->queryContainer,
                $this->localeFacade
            );
            $fields[] = $this->addSubForm('translations')
                ->setForm($form)
            ;
        }
    }
}
