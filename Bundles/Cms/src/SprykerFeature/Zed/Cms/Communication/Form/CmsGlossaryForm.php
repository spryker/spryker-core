<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;


use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CmsGlossaryForm extends AbstractForm
{

    const FK_PAGE = 'fkPage';
    const PLACEHOLDER = 'placeholder';
    const GLOSSARY_KEY = 'glossary_key';
    const ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';


    protected $glossaryQuery;

    protected $type;

    protected $idPage;

    protected $idMapping;

    /**
     * @param string $type
     */

    public function __construct($glossaryQuery, $type, $idPage, $idMapping)
    {
        $this->glossaryQuery = $glossaryQuery;
        $this->type = $type;
        $this->idPage = $idPage;
        $this->idMapping = $idMapping;
    }

    /**
     * @return CmsRedirectForm
     */
    protected function buildFormFields()
    {
        return $this->addHidden(self::FK_PAGE,
            [
                'label' => 'Page Id'
            ])
        ->addHidden(self::ID_KEY_MAPPING,
            [
                'label' => 'key mapping Id'
            ])
        ->addText(self::PLACEHOLDER, [
            'label' => 'Placeholder',
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 256]),
            ],
        ])
          ->addAutosuggest(self::GLOSSARY_KEY, [
              'label' => 'Glossary Key',
              'url' => '/glossary/key/suggest',
          ]);
    }


    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $formItems = [
            self::FK_PAGE => $this->idPage,
            self::ID_KEY_MAPPING => $this->idMapping
        ];
        if ($this->glossaryQuery) {
            $glossaryMapping = $this->glossaryQuery->findOne();

            if ($glossaryMapping) {
                $formItems[self::PLACEHOLDER] = $glossaryMapping->getPlaceholder();
                $formItems[self::GLOSSARY_KEY] = $glossaryMapping->getKeyname();
            }
        }

        return $formItems;
    }

}
