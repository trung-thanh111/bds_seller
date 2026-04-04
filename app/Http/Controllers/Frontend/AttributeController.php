<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Attribute\AttributeRepository;
use App\Repositories\Attribute\AttributeCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Services\V1\RealEstate\RealEstateService;
use App\Services\V1\Attribute\AttributeService;
use App\Services\V1\Core\WidgetService;
use App\Models\Attribute;
use App\Repositories\RealEstate\AgentRepo;

class AttributeController extends FrontendController
{
    protected $attributeRepository;
    protected $attributeCatalogueRepository;
    protected $realEstateRepository;
    protected $realEstateService;
    protected $agentRepo;
    protected $attributeService;
    protected $widgetService;
    protected $realEstateCatalogueRepository;

    protected $amenityRepository;

    public function __construct(
        AttributeRepository $attributeRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        RealEstateService $realEstateService,
        AgentRepo $agentRepo,
        AttributeService $attributeService,
        WidgetService $widgetService,
        \App\Repositories\RealEstate\RealEstateCatalogueRepository $realEstateCatalogueRepository,
        \App\Repositories\Amenity\AmenityRepository $amenityRepository
    ) {
        parent::__construct();
        $this->attributeRepository = $attributeRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->realEstateService = $realEstateService;
        $this->agentRepo = $agentRepo;
        $this->attributeService = $attributeService;
        $this->widgetService = $widgetService;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->amenityRepository = $amenityRepository;
    }

    public function index($id, Request $request, $page = 1)
    {
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        if (!$attribute) {
            abort(404);
        }

        $breadcrumb = null;
        if ($attribute->attribute_catalogues->isNotEmpty()) {
            $breadcrumb = $this->attributeCatalogueRepository->breadcrumb($attribute->attribute_catalogues->first(), $this->language);
        }

        $priceField = $request->input('transaction_type') == '75' ? 'price_rent' : 'price_sale';
        $sorts = [
            'id:desc' => 'Mặc định',
            $priceField . ':asc' => 'Giá thấp đến cao',
            $priceField . ':desc' => 'Giá cao đến thấp',
            'area:asc' => 'Diện tích nhỏ đến lớn',
            'area:desc' => 'Diện tích lớn đến nhỏ',
        ];

        $sort = ['real_estates.id', 'DESC'];
        if ($request->filled('sort')) {
            $sortArr = explode(':', $request->input('sort'));
            if (count($sortArr) == 2) {
                $sort = ['real_estates.' . $sortArr[0], $sortArr[1]];
            }
        }

        $realEstates = $this->realEstateService->paginate($request, $this->language, null, $page, ['path' => $attribute->canonical], $sort, $attribute->id);

        // Optimize attributeMap fetching (IDs used for units, directions, etc. in real_estate_card)
        $attributeIds = [];
        foreach ($realEstates as $re) {
            $fields = [
                'price_unit', 'transaction_type', 'house_direction', 
                'balcony_direction', 'ownership_type', 'land_type', 
                'interior', 'floor'
            ];
            foreach ($fields as $field) {
                if (!empty($re->$field)) $attributeIds[] = $re->$field;
            }
        }
        $attributeIds = array_unique(array_filter($attributeIds));

        $attributeMap = [];
        if (!empty($attributeIds)) {
            $attributeMap = \App\Models\Attribute::whereIn('id', $attributeIds)
                ->with(['languages' => function ($q) {
                    $q->where('language_id', $this->language);
                }])
                ->get()
                ->pluck('languages.0.pivot.name', 'id')
                ->toArray();
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.realestate.catalogue.listing_results', compact('realEstates', 'attributeMap'))->render(),
                'total' => number_format($realEstates->total(), 0, ',', '.'),
                'sortLabel' => $sorts[$request->input('sort')] ?? 'Mặc định'
            ]);
        }

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-projects'],
            ['keyword' => 'product-category', 'children' => true],
        ], $this->language);

        $system = $this->system;
        $seo = seo($attribute, $page);
        $config = $this->config();

        $template = 'frontend.realestate.catalogue.index';
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'attribute',
            'realEstates',
            'widgets',
            'attributeMap',
            'sorts'
        ));
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'css' => [
                'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css',
                'frontend/resources/style.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/owl.carousel.min.js',
                'frontend/resources/library/js/carousel.js',
                'frontend/resources/library/js/filter.js',
            ],
        ];
    }
}
