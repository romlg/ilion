<?php

namespace App\Http\Controllers\ASystem;

use App\Helpers\ExcelParser\ExcelParser;
use App\Http\Requests\UploadImportModelRequest;
use App\Models\Layout;
use App\Models\LayoutMaterial;
use App\Models\Nomenclature;
use App\Models\Objct;
use App\Models\PatternPrices;
use App\Models\Specification;
use App\Models\SpecUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SpecificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginator = Specification::paginate(10);
        return view('asystem.specifications.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $objects = Objct::all();
        return view('asystem.specifications.create', compact('objects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'title' => 'required|min:2|max:255|unique:specifications'
        ]);

        $data = $request->input();

        $item = new Specification($data);
        $item->save();

        if($item) {
            return redirect()
                ->route('specification.edit', $item->spec_id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $item = Specification::find($id);
        $specUnits = SpecUnit::where('spec_id', $id)->active()->get();
        $nomenclatures = Nomenclature::all();

        $objects =  Objct::all();
        return view('asystem.specifications.edit',
            compact('item', 'objects', 'specUnits', 'nomenclatures')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validatedData = $request->validate([
            'title' => 'required|min:2|max:255'
        ]);

        $item = Specification::find($id);

        $data = $request->all();

        if (array_key_exists("save", $data)) {
            $modeMsg = 'сохранено';
            $nomenclatures = $data['nomenclatures'];
            $nomenclaturesCount = $data['nomenclaturesCount'];

            foreach ($nomenclatures AS $key => $nomenclature) {
                if(in_array(null, $nomenclatures) || in_array(null, $nomenclaturesCount)) {
                    return back()
                        ->withErrors(['msg' => "Ошибка сохранения. Номенклатура не заполнена"])
                        ->withInput();
                }
                SpecUnit::updateOrInsert(['spec_id' => $id, 'n_id' => $nomenclature, 'count' => $nomenclaturesCount[$key], 'ver' => 0, 'is_active' => 1]);
            }
            unset($data['nomenclaturesSave']);
            unset($data['nomenclaturesSaveCount']);
        }

        if (array_key_exists("update", $data)) {
            $modeMsg = 'обновленно';

            if(!isset($data['nomenclaturesUpdate'])) {
                return back()
                    ->withErrors(['msg' => "Ошибка сохранения. Номенклатура не заполнена"])
                    ->withInput();
            }

            $nomenclatures = $data['nomenclaturesUpdate'];
            $nomenclaturesCount = $data['nomenclaturesUpdateCount'];

            foreach ($nomenclatures as $key => $nomenclature) {  //updata count
                $SpecUnit = SpecUnit::find($nomenclature);
                $SpecUnit->count = $nomenclaturesCount[$key];
                $SpecUnit->save();
            }

            $SpecUnitsId = SpecUnit::where('spec_id', $id)->get('sunit_id')->toArray();  //updata count
            foreach ($SpecUnitsId as $unit) {
                $units[] = $unit['sunit_id'];
            }
            $SpecUnitsDelete = array_diff($units, $nomenclatures);
            SpecUnit::destroy($SpecUnitsDelete);

            unset($data['nomenclaturesUpdate']);
            unset($data['nomenclaturesUpdateCount']);
        }

        $result = $item
            ->fill($data)
            ->save();

        if ($result) {
            return redirect()
                ->route('specification.edit', $item->spec_id)
                ->with(['success' => "Успешно {$modeMsg}"]);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка"])
                ->withInput();
        }
    }

    public function upload($id)
    {
        return view('asystem.specifications.upload', ['id' => $id]);
    }

    public function uploadSave(UploadImportModelRequest $request)
    {
        $id =  $request->input('id');
        $originalFile = $request->file('import_file');
        $ext = $originalFile->getClientOriginalExtension();

        $sheetData = [];
        if ($ext == 'xlsx') {
            $sheetData = ExcelParser::get_array_xlsx($request->file('import_file'));
        }  elseif ($ext == 'xls') {
            $sheetData = ExcelParser::get_array_xls($request->file('import_file'));
        }

        $nomenclatures = Nomenclature::all()->toArray();

        //ищем максимальную версию и инкриминируем
        $maxVer = SpecUnit::where('spec_id', $id)->max('ver') + 1;
        //деактивация всех номенклатур
        SpecUnit::where('spec_id', $id)->update(['is_active' => 0]);

        $resultNotSearch = [];

        foreach ($sheetData as $data) {

            $title = $data[2];
            $count = $data[4];

            $chk = false;
            foreach ($nomenclatures as $nomenclature) {
                if ($nomenclature["title"] == $title) {
                    SpecUnit::updateOrInsert(['spec_id' => $id, 'n_id' => $nomenclature["n_id"], 'count' => $count, 'ver' => $maxVer, 'is_active' => 1]);
                    $chk = true;
                }
            }
            if(!$chk && !is_null($title)) {
                $resultNotSearch[] = $title;
            }
        }

        if (empty($resultNotSearch)) {
            return redirect()
                ->route('specification.edit', $id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            $resultSeparated = implode(", ", $resultNotSearch);
            return redirect()
                ->route('specification.edit', $id)
                ->withErrors(['msg' => "Ошибка сохранения. Не добавлена наменклатура: {$resultSeparated}"])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generate($id)
    {
        $specification = Specification::find($id);

        $generateCO = $this->generateCommercialOffers($specification);
        $layout = $this->layoutSave($specification);
        $this->layoutSaveMaterial($specification, $layout, $generateCO);

        return $generateCO;
    }

    protected function generateCommercialOffers(Specification $specification): array
    {
        foreach ($specification->nomenclatures as $nomenclature) {
            $PP = PatternPrices::where('title', $nomenclature->title)->first();

            if (is_null($PP)) {
                echo "Шаблон расценки для наменклатуры {$nomenclature->title} не добавлен";
                die();
            }

            $generateCO[$nomenclature->n_id]['work'] = $PP->worksForCommercialOffer->all();
            $generateCO[$nomenclature->n_id]['pattern_material'] = $PP->patternMaterialsForCommercialOffer->all();
            $generateCO[$nomenclature->n_id]['material'] = $PP->expendableMaterialsForCommercialOffer->all();
        }
        return $generateCO;
    }

    protected function layoutSave(Specification $specification): Layout
    {
        $date = Carbon::now()->format('d.m.Y H:i:s');
        $title = "раскладка {$specification->title} {$date}";
        $itemLayout = new Layout(['title' => $title]);
        $itemLayout->save();

        return $itemLayout;
    }

    protected function layoutSaveMaterial(Specification $specification, Layout $layout, Array $generateCO)
    {
        foreach ($generateCO as $nomenclature => $CO) {
            $nomenclatureCount = $specification->units->where('n_id', $nomenclature)->first()->count;
            foreach ($CO as $type => $values) {
                foreach ($values as $value) {

                    $typeId = $value->getOriginal("{$type}_id");

                    $itemLayoutMaterial = new LayoutMaterial(['layout_id' => $layout->layout_id, 'position_id' => $typeId,
                        'count' => $nomenclatureCount, 'type' => $type]);
                    $itemLayoutMaterial->save();
                }
            }
        }
    }
}