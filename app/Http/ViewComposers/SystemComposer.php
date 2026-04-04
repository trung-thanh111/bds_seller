<?php  
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Core\SystemRepository;

class SystemComposer
{

    protected $language;
    protected $systemRepository;

    public function __construct(
        SystemRepository $systemRepository,
        $language
    ){
        $this->systemRepository = $systemRepository;
        $this->language = $language;
    }

    protected static $cachedSystem = null;

    public function compose(View $view)
    {
        if (static::$cachedSystem === null) {
            $system = $this->systemRepository->findByCondition(
                [
                    ['language_id', '=', $this->language]
                ],
                TRUE
            );
            static::$cachedSystem = convert_array($system, 'keyword', 'content');
        }
        
        $view->with('system', static::$cachedSystem);
    }
}