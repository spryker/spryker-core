<?php

namespace SprykerEngine\Zed\Translation\Business;

class Translator extends \Symfony\Component\Translation\Translator
{
    /**
     * @param string $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $translation = parent::trans($id, $parameters, $domain, $locale);

        if ($translation === $id && strstr($id, '.') !== false && $this->has(substr($id, strpos($id, '.') + 1))) {
            $translation = parent::trans(
                substr($id, strpos($id, '.') + 1),
                $parameters,
                $domain,
                $locale
            );
        }

        return $translation;
    }

    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        $translation = parent::transChoice($id, $number, $parameters, $domain, $locale);

        if ($translation === $id && strstr($id, '.') !== false && $this->has(substr($id, strpos($id, '.') + 1))) {
            $translation = parent::transChoice(
                substr($id, strpos($id, '.') + 1),
                $number,
                $parameters,
                $domain,
                $locale
            );
        }

        return $translation;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->catalogues[$this->locale]->has($id);
    }
}