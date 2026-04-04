<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Language;
use App\Models\System;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    protected $language;
    protected $systemRepository;
    protected $system;

    public function __construct(
        // SystemRepository $systemRepository
    ){

        $this->setLanguage();
        $this->setSystem();

    }

    public function setLanguage(){
        $locale = app()->getLocale(); // vn en cn
        // $language = Language::where('canonical', $locale)->first();
        $this->language = 1;
    }

    protected static $cachedSystem = null;

    public function setSystem(){
        if (static::$cachedSystem === null) {
            static::$cachedSystem = convert_array(System::where('language_id', $this->language)->get(), 'keyword', 'content');
        }
        $this->system = static::$cachedSystem;
    }
   

}
