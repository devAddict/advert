<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 29/11/2016
 * Time: 21:55
 */

namespace DA\PlatformBundle\Antispam;


class DAAntispam
{
    private $mailer;
    private $locale;
    private $minlength;

    /**
     * DAAntispam constructor.
     */
    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer    = $mailer;
        $this->locale    = $locale;
        $this->minlength = (int)$minLength;
    }

    public function isSpam($text)
    {
        return strlen($text) < $this->minlength;
    }
}