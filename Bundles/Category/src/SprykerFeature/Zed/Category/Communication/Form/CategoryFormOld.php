<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Form;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CategoryCommunication;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryFormOld extends AbstractForm
{

    const NAME = 'name';
    const IS_ACTIVE = 'is_active';
    const ID_CATEGORY = 'id_category';

    /**
     * @var FactoryInterface|CategoryCommunication
     */
    protected $factory;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @param Request $request
     * @param FactoryInterface $factory
     * @param LocaleTransfer $locale
     * @param QueryContainerInterface $queryContainer
     */
    public function __construct(
        Request $request,
        FactoryInterface $factory,
        LocaleTransfer $locale,
        QueryContainerInterface $queryContainer = null
    ) {
        parent::__construct($request, $queryContainer);
        $this->factory = $factory;
        $this->locale = $locale;
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        if ($this->getIdCategory()) {
            return [
                self::ID_CATEGORY => $this->getIdCategory(),
                self::NAME => $this->stateContainer->getRequestValue(self::NAME),
                self::IS_ACTIVE => $this->stateContainer->getRequestValue(self::IS_ACTIVE),
            ];
        }

        return [
            self::IS_ACTIVE => true,
        ];
    }

    public function addFormFields()
    {
        $this->addField(self::ID_CATEGORY);
        $this->addField(self::NAME)
            ->setConstraints(
                [
                    new Assert\Type([
                        'type' => 'string',
                    ]),
                    new Assert\NotBlank(),
                    $this->factory->createConstraintCategoryNameExists(
                        $this->queryContainer,
                        $this->getIdCategory(),
                        $this->getLocale()
                    ),
                ]
            );

        $this->addField(self::IS_ACTIVE)
            ->setConstraints(
                [
                    new Assert\Type(
                        ['type' => 'boolean']
                    ),
                ]
            );
    }

    /**
     * @return int
     */
    protected function getIdCategory()
    {
        return $this->stateContainer->getRequestValue(self::ID_CATEGORY);
    }

    /**
     * @return LocaleTransfer
     */
    protected function getLocale()
    {
        return $this->locale;
    }

}
