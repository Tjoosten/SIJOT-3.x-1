<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

/**
 * Class LanguageMiddleware
 *
 * @package App\Http\Middleware
 */
class LanguageMiddleware
{
    /**
     * The supported languages prefixes.
     *
     * @var array
     */
    protected static $supportedLanguages = ['nl', 'en', 'fr'];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = (Input::get('lang')) ?: Session::get('lang');
        $this->setSupportedLanguage($language);
        return $next($request);
    }

    /**
     * Check if the language is supported.
     *
     * @param  string $lang
     * @return bool
     */
    private function isLanguageSupported($lang)
    {
        return in_array($lang, self::$supportedLanguages);
    }

    /**
     * Set the supported language for the user.
     *
     * @param string $lang
     */
    private function setSupportedLanguage($lang)
    {
        if ($this->isLanguageSupported($lang)) {
            App::setLocale($lang);
            Session::put('lang', $lang);
        }
    }
}