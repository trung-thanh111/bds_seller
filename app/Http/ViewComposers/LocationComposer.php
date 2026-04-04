<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\AgentRepo;
use App\Repositories\Amenity\AmenityRepository;
use App\Repositories\Attribute\AttributeRepository;
use Illuminate\Support\Facades\Log;

class LocationComposer
{
    protected $realEstateCatalogueRepository;
    protected $projectCatalogueRepository;
    protected $realEstateRepository;
    protected $projectRepository;
    protected $agentRepo;
    protected $amenityRepository;
    protected $attributeRepository;
    protected $language;

    protected static $cachedRealEstateCatalogues = null;
    protected static $cachedProjectCatalogues = null;
    protected static $cachedTopProvinces = null;
    protected static $cachedProvincesAfter = null;
    protected static $cachedProvincesBefore = null;
    protected static $cachedAmenities = null;
    protected static $cachedFurnitures = null;
    protected static $cachedHouseDirections = null;
    protected static $cachedBalconyDirections = null;
    protected static $cachedNewestRealEstates = null;
    protected static $cachedFeaturedProjects = null;
    protected static $cachedAgent = null;

    public function __construct(
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        ProjectCatalogueRepository $projectCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        ProjectRepository $projectRepository,
        AgentRepo $agentRepo,
        AmenityRepository $amenityRepository,
        AttributeRepository $attributeRepository,
        $language
    ) {
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->projectRepository = $projectRepository;
        $this->agentRepo = $agentRepo;
        $this->amenityRepository = $amenityRepository;
        $this->attributeRepository = $attributeRepository;
        $this->language = $language;
    }

    public function compose(\Illuminate\View\View $view)
    {
        if (static::$cachedProvincesAfter === null) {
            static::$cachedProvincesAfter = $this->getProvinces('after');
        }

        if (static::$cachedProvincesBefore === null) {
            static::$cachedProvincesBefore = $this->getProvinces('before');
        }

        if (static::$cachedRealEstateCatalogues === null) {
            static::$cachedRealEstateCatalogues = $this->getCatalogues();
        }

        if (static::$cachedProjectCatalogues === null) {
            static::$cachedProjectCatalogues = $this->getProjectCatalogues();
        }

        if (static::$cachedAmenities === null) {
            static::$cachedAmenities = $this->getAmenities();
        }

        if (static::$cachedFurnitures === null) {
            static::$cachedFurnitures = $this->getFurnitures();
        }

        if (static::$cachedHouseDirections === null) {
            static::$cachedHouseDirections = $this->getAttributesByCatalogueId(3);
        }

        if (static::$cachedBalconyDirections === null) {
            static::$cachedBalconyDirections = $this->getAttributesByCatalogueId(4);
        }

        if (static::$cachedAgent === null) {
            static::$cachedAgent = $this->getAgent();
        }

        if (static::$cachedTopProvinces === null) {
            static::$cachedTopProvinces = $this->getTopProvinces();
        }

        $view->with('provinces', static::$cachedProvincesAfter);
        $view->with('old_provinces', static::$cachedProvincesBefore);
        $view->with('realEstateCatalogues', static::$cachedRealEstateCatalogues);
        $view->with('propertyTypes', static::$cachedRealEstateCatalogues);
        $view->with('projectCatalogues', static::$cachedProjectCatalogues);
        $view->with('amenities', static::$cachedAmenities);
        $view->with('furnitures', static::$cachedFurnitures);
        $view->with('houseDirections', static::$cachedHouseDirections);
        $view->with('balconyDirections', static::$cachedBalconyDirections);
        $view->with('agent', static::$cachedAgent);
        $view->with('topProvinces', static::$cachedTopProvinces);
    }

    private function getAmenities()
    {
        return $this->amenityRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['order', 'asc']);
    }

    private function getFurnitures()
    {
        return $this->getAttributesByCatalogueId(2);
    }

    private function getAttributesByCatalogueId($catalogueId)
    {
        return $this->attributeRepository->findByCondition(
            condition: [['attribute_catalogue_id', '=', $catalogueId], config('apps.general.defaultPublish')],
            flag: true,
            relation: ['languages' => function ($query) {
                $query->where('language_id', $this->language);
            }],
            orderBy: ['id', 'asc']
        );
    }

    private function getNewestRealEstates()
    {
        return $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'], [], [], 8);
    }

    private function getFeaturedProjects()
    {
        return $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages' => function ($q) {
            $q->where('language_id', $this->language);
        }], ['id', 'DESC'], [], [], 8);
    }

    private function getAgent()
    {
        return $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);
    }

    private function getTopProvinces()
    {
        return \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw("(
            SELECT province_code, province_name, count(*) as cnt 
            FROM real_estates 
            WHERE publish = 2 AND deleted_at IS NULL 
            GROUP BY province_code, province_name
            UNION ALL
            SELECT province_code, province_name, count(*) as cnt 
            FROM projects 
            WHERE publish = 2 AND deleted_at IS NULL 
            GROUP BY province_code, province_name
        ) as combined"))
            ->select('province_code', 'province_name', \Illuminate\Support\Facades\DB::raw('SUM(cnt) as total_count'))
            ->groupBy('province_code', 'province_name')
            ->orderBy('total_count', 'DESC')
            ->limit(20) // Show more in sidebar than homepage
            ->get();
    }

    private function getCatalogues()
    {
        $publishCondition = [config('apps.general.defaultPublish')];
        return $this->realEstateCatalogueRepository->findByCondition(
            $publishCondition,
            true,
            ['languages' => function ($query) {
                $query->where('language_id', $this->language);
            }],
            ['lft', 'asc']
        );
    }

    private function getProjectCatalogues()
    {
        return $this->projectCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
            ],
            true,
            ['languages' => function ($query) {
                $query->where('language_id', $this->language);
            }],
            ['lft', 'asc']
        );
    }

    private function getProvinces(string $source): array
    {
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if (!File::exists($filePath)) return [];

        $data = json_decode(File::get($filePath), true) ?? [];
        $provinces = [];
        foreach ($data as $item) {
            if (isset($item['codename']) && isset($item['name'])) {
                $provinces[$item['codename']] = $item['name'];
            }
        }
        return $provinces;
    }
}
