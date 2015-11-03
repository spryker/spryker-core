<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CardScheme;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\False;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Constraints\Issn;
use Symfony\Component\Validator\Constraints\Language;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Locale;
use Symfony\Component\Validator\Constraints\Luhn;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\NotIdenticalTo;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Null;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;

class ConstraintsPlugin extends AbstractPlugin
{

    /**
     * @param null $options
     *
     * @return NotBlank
     */
    public function createConstraintNotBlank($options = null)
    {
        return new NotBlank($options);
    }

    /**
     * @return Blank
     */
    public function createConstraintBlank()
    {
        return new Blank();
    }

    /**
     * @param null $options
     *
     * @return NotNull
     */
    public function createConstraintNotNull($options = null)
    {
        return new NotNull($options);
    }

    /**
     * @param null $options
     *
     * @return Null
     */
    public function createConstraintNull($options = null)
    {
        return new Null($options);
    }

    /**
     * @param null $options
     *
     * @return True
     */
    public function createConstraintTrue($options = null)
    {
        return new True($options);
    }

    /**
     * @param null $options
     *
     * @return False
     */
    public function createConstraintFalse($options = null)
    {
        return new False($options);
    }

    /**
     * @param null $options
     *
     * @return Type
     */
    public function createConstraintType($options = null)
    {
        return new Type($options);
    }

    /**
     * @param null $options
     *
     * @return Email
     */
    public function createConstraintEmail($options = null)
    {
        return new Email($options);
    }

    /**
     * @param null $options
     *
     * @return Length
     */
    public function createConstraintLength($options = null)
    {
        return new Length($options);
    }

    /**
     * @param null $options
     *
     * @return Url
     */
    public function createConstraintUrl($options = null)
    {
        return new Url($options);
    }

    /**
     * @param null $options
     *
     * @return Regex
     */
    public function createConstraintRegex($options = null)
    {
        return new Regex($options);
    }

    /**
     * @param null $options
     *
     * @return Ip
     */
    public function createConstraintIp($options = null)
    {
        return new Ip($options);
    }

    /**
     * @param null $options
     *
     * @return Uuid
     */
    public function createConstraintUuid($options = null)
    {
        return new Uuid($options);
    }

    /**
     * @param null $options
     *
     * @return Range
     */
    public function createConstraintRange($options = null)
    {
        return new Range($options);
    }

    /**
     * @param null $options
     *
     * @return EqualTo
     */
    public function createConstraintEqualTo($options = null)
    {
        return new EqualTo($options);
    }

    /**
     * @param null $options
     *
     * @return NotEqualTo
     */
    public function createConstraintNotEqualTo($options = null)
    {
        return new NotEqualTo($options);
    }

    /**
     * @param null $options
     *
     * @return IdenticalTo
     */
    public function createConstraintIdenticalTo($options = null)
    {
        return new IdenticalTo($options);
    }

    /**
     * @param null $options
     *
     * @return NotIdenticalTo
     */
    public function createConstraintNotIdenticalTo($options = null)
    {
        return new NotIdenticalTo($options);
    }

    /**
     * @param null $options
     *
     * @return LessThan
     */
    public function createConstraintLessThan($options = null)
    {
        return new LessThan($options);
    }

    /**
     * @param null $options
     *
     * @return LessThanOrEqual
     */
    public function createConstraintLessThanOrEqual($options = null)
    {
        return new LessThanOrEqual($options);
    }

    /**
     * @param null $options
     *
     * @return GreaterThan
     */
    public function createConstraintGreaterThan($options = null)
    {
        return new GreaterThan($options);
    }

    /**
     * @param null $options
     *
     * @return GreaterThanOrEqual
     */
    public function createConstraintGreaterThanOrEqual($options = null)
    {
        return new GreaterThanOrEqual($options);
    }

    /**
     * @param null $options
     *
     * @return Date
     */
    public function createConstraintDate($options = null)
    {
        return new Date($options);
    }

    /**
     * @param null $options
     *
     * @return DateTime
     */
    public function createConstraintDateTime($options = null)
    {
        return new DateTime($options);
    }

    /**
     * @param null $options
     *
     * @return Time
     */
    public function createConstraintTime($options = null)
    {
        return new Time($options);
    }

    /**
     * @param null $options
     *
     * @return Choice
     */
    public function createConstraintChoice($options = null)
    {
        return new Choice($options);
    }

    /**
     * @param null $options
     *
     * @return Collection
     */
    public function createConstraintCollection($options = null)
    {
        return new Collection($options);
    }

    /**
     * @param null $options
     *
     * @return Count
     */
    public function createConstraintCount($options = null)
    {
        return new Count($options);
    }

    /**
     * @param null $options
     *
     * @return Language
     */
    public function createConstraintLanguage($options = null)
    {
        return new Language($options);
    }

    /**
     * @param null $options
     *
     * @return Locale
     */
    public function createConstraintLocale($options = null)
    {
        return new Locale($options);
    }

    /**
     * @param null $options
     *
     * @return Country
     */
    public function createConstraintCountry($options = null)
    {
        return new Country($options);
    }

    /**
     * @param null $options
     *
     * @return File
     */
    public function createConstraintFile($options = null)
    {
        return new File($options);
    }

    /**
     * @param null $options
     *
     * @return Image
     */
    public function createConstraintImage($options = null)
    {
        return new Image($options);
    }

    /**
     * @param null $options
     *
     * @return CardScheme
     */
    public function createConstraintCardScheme($options = null)
    {
        return new CardScheme($options);
    }

    /**
     * @param null $options
     *
     * @return Currency
     */
    public function createConstraintCurrency($options = null)
    {
        return new Currency($options);
    }

    /**
     * @param null $options
     *
     * @return Luhn
     */
    public function createConstraintLuhn($options = null)
    {
        return new Luhn($options);
    }

    /**
     * @param null $options
     *
     * @return Iban
     */
    public function createConstraintIban($options = null)
    {
        return new Iban($options);
    }

    /**
     * @param null $options
     *
     * @return Isbn
     */
    public function createConstraintIsbn($options = null)
    {
        return new Isbn($options);
    }

    /**
     * @param null $options
     *
     * @return Issn
     */
    public function createConstraintIssn($options = null)
    {
        return new Issn($options);
    }

    /**
     * @param null $options
     *
     * @return Callback
     */
    public function createConstraintCallback($options = null)
    {
        return new Callback($options);
    }

    /**
     * @param null $options
     *
     * @return Expression
     */
    public function createConstraintExpression($options = null)
    {
        return new Expression($options);
    }

    /**
     * @param null $options
     *
     * @return All
     */
    public function createConstraintAll($options = null)
    {
        return new All($options);
    }

    /**
     * @param null $options
     *
     * @return UserPassword
     */
    public function createConstraintUserPassword($options = null)
    {
        return new UserPassword($options);
    }

    /**
     * @param null $options
     *
     * @return Valid
     */
    public function createConstraintValid($options = null)
    {
        return new Valid($options);
    }

    /**
     * @param null $options
     *
     * @return Required
     */
    public function createConstraintRequired($options = null)
    {
        return new Required($options);
    }
}
