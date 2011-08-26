<?php
class I18n
{
    public static function SetLocale($locale = null)
    {
        if (!$locale)
        {
            if (isset($_COOKIE['locale']))
            {
                $locale = $_COOKIE['locale'];
            } else {
                global $settings;
                $locale = $settings['locale'];
            }
        }
        
        putenv("LC_ALL=$locale");
        setlocale(LC_ALL, $locale);
        setcookie("locale", $locale, time() + (3600*24*30*12));
    }
    
    public static function BindDomain($domain)
    {
        bindtextdomain($domain, "./application/i18n");
        textdomain($domain);        
    }
}
?>