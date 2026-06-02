<?php


class Model
{

    protected function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }


    protected function validateName($name)
    {
        return strlen($name) >= 2 && !preg_match('/[0-9]/', $name);
    }

    protected function validateNumber($number)
    {
        $regexNumber  = '/^(?!\(99\) 99999\-9999$)\((?:[14689][1-9]|2[12478]|3[1234578]|5[1345]|7[134579])\) (?:9[1-9][0-9]{3}|[2-8][0-9]{3})\-[0-9]{4}$/';
        return (bool) preg_match($regexNumber, $number);
    }

    protected function validateMinLength($text, $minLength)
    {
        return strlen(trim($text)) >= $minLength;
    }

    protected function validateMaxLength($text, $maxLength)
    {
        return strlen(trim($text)) <= $maxLength;
    }

    protected function sanitize($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    protected function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    protected function formatDate($date, $format = 'd/m/Y H:i:s')
    {
        return date($format, strtotime($date));
    }
}
