<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class KeyForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_GLOSSARY_KEY = 'id_glossary_key';

    /**
     * @var SpyGlossaryKeyQuery
     */
    protected $keyQuery;

    /**
     * @var SpyGlossaryKeyQuery
     */
    protected $subQuery;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $idGlossaryKey;

    public function __construct(SpyGlossaryKeyQuery $keyQuery, SpyGlossaryKeyQuery $subQuery, $type = 'add', $idGlossaryKey = false)
    {
        $this->keyQuery = $keyQuery;
        $this->subQuery = $subQuery;
        $this->type = $type;

        $this->idGlossaryKey = $idGlossaryKey;
    }

    /**
     * @inheritDoc
     */
    protected function populateFormFields()
    {
        $result = [];

        $idGlossaryKey = $this->request->get(self::ID_GLOSSARY_KEY);
        if (!empty($idGlossaryKey)) {
            $glossaryKeyDetails = $this->keyQuery->findOneByIdGlossaryKey($idGlossaryKey);

            if (!empty($glossaryKeyDetails)) {
                $result = $glossaryKeyDetails->toArray();
            }
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function cutTablePrefix($key)
    {
        $position = mb_strpos($key, '.');

        return (false !== $position) ? mb_substr($key, $position + 1) : $key;
    }

    /**
     * @return $this
     */
    public function buildFormFields()
    {
        $isActiveConstraints = ['label' => 'Is active?'];

        if (self::ADD === $this->type) {
            $isActiveConstraints['attr'] = [
                'checked' => 'checked',
            ];
        }

        $this->addText('key', [
            'label' => 'Name',
            'constraints' => [
                new Callback([
                    'methods' => [
                        function ($key, ExecutionContext $context) {
                            $result = false;

                            if (self::ADD === $this->type) {
                                $details = $this->subQuery->filterByKey($key)
                                    ->find()
                                    ->count()
                                ;
                                $result = ($details > 0);
                            } else {
                                $details = $this->subQuery->filterByKey($key)
                                    ->findOne()
                                ;

                                if (!empty($details)) {
                                    $details = $details->toArray();

                                    $key = $this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY);
                                    $idGlossaryKey = !empty($details[$key]) ? strval($details[$key]) : false;

                                    $result = ($idGlossaryKey !== $this->idGlossaryKey);
                                }
                            }

                            if ($result) {
                                $context->addViolation('Key is already used');
                            }
                        },
                    ],
                ]),
                new NotBlank(),
                new Required(),
            ],
        ])
            ->addCheckbox('is_active', $isActiveConstraints)
        ;

        $this->addSubmit('submit', [
            'label' => (self::UPDATE === $this->type ? 'Update' : 'Add'),
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }

}
