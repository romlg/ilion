<?php

namespace App\Http\Controllers\ASystem\Document;

use App\Http\Controllers\ASystem\BaseController;
use App\Http\Requests\UpdateLayoutRequest;
use App\Models\Layout\Layout;
use App\Models\Layout\LayoutMaterial;
use App\Models\Layout\MaterialToLayoutPatternMaterial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;

class LayoutController extends BaseController
{
    const
        VIEW_ROOT = 'asystem.layouts',
        VIEW_INDEX = self::VIEW_ROOT.'.index',
        VIEW_EDIT = self::VIEW_ROOT.'.edit',
        VIEV_SHOW = self::VIEW_ROOT.'.show'
    ;
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view(
            self::VIEW_INDEX,
            [
                'paginator' => app(Layout::class)
                    ->select(['layout_id', 'title'])
                    ->paginate(10)
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {   
        $item = app(Layout::class)
            ->select(['layout_id', 'title'])
            ->with([
                'layoutMaterial.position',
                'layoutMaterial.selectedMaterial'
            ])
            ->whereKey($id)
            ->get()
            ->first()
        ;
        
        $item->layoutMaterial = $item->layoutMaterial->sort(
            function(LayoutMaterial $a, LayoutMaterial $b) {
                if ($a->isPattern() && $b->isPattern() || !$a->isPattern() && !$b->isPattern()) {
                    return 0;
                }
                return $a->isPattern() ? -1 : 1;
            }
        );

        return view(self::VIEW_EDIT, [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateLayoutRequest $request, $id)
    {   
        /** @var Layout $layout **/
        $layout = app(Layout::class)
            ->select(['layout_id', 'title'])
            ->with(['layoutMaterial.selectedMaterial'])
            ->whereKey($id)
            ->get()
            ->first()
        ;
        if(empty($layout)) {
            return $this->returnBack("Запись id=[$id] не найдена");
        }

        $result = $layout->update(['title' => $request->get('title')]);
        if (!$result) {
            return $this->returnBack();
        }
             
        $result = $this->manageSavedMaterials(
            $layout->getPatterns(),
            $request->getMaterialsForPatterns()
        );
        if (!$result) {
            return $this->returnBack();
        }
        
        if ($request->isCO()) {
            return redirect()
                ->route('layout.show', $layout->layout_id)
            ;
        } else {
            return redirect()
                ->route('layout.edit', $layout->layout_id)
                ->with(['success' => "Успешно сохранено"])
            ;
        }
    }
    
    private function returnBack(string $message = 'Ошибка сохранения')
    {
        return back()
            ->withErrors(['msg' => $message])
            ->withInput()
        ;
    }
    
    /**
     * 
     * @param Collection $patterns
     * @param array $requestData
     * @return bool
     */
    private function manageSavedMaterials(
        Collection $patterns,
        array $requestData
    ): bool {
        $result = true;
        $toDelete = [];
        /** @var LayoutMaterial $layoutMaterial **/
        foreach ($patterns as $layoutMaterial) {
            
            $layoutMaterialId = $layoutMaterial->id;
            
            if (!array_key_exists($layoutMaterialId, $requestData)) {
                continue;
            }
            
            $materialId = $requestData[$layoutMaterialId];
            $selectedMaterial = $layoutMaterial->selectedMaterial;

            if (!$materialId) {
                if ($selectedMaterial) {
                    $toDelete = [$layoutMaterialId];      
                }
            } elseif (!$selectedMaterial || $selectedMaterial->material_id != $materialId) {
                $result = (bool)MaterialToLayoutPatternMaterial::updateOrInsert(
                    ['layout_material_id' => $layoutMaterialId],
                    ['material_id' => $materialId]
                );
                if (!$result) {
                    return false;
                }   
            }
        }
        
        if (count($toDelete)) {
            $result = MaterialToLayoutPatternMaterial::where('layout_material_id', $toDelete)
                ->delete()
            ;
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        /** @var Layout $layout **/
        $layout = app(Layout::class)
            ->select(['layout_id', 'title'])
            ->with([
                'layoutMaterial.selectedMaterial',
                'layoutMaterial.position'
            ])
            ->whereKey($id)
            ->get()
            ->first()
        ;
        if(empty($layout)) {
            return $this->returnBack("Запись id=[$id] не найдена");
        }
        
        foreach ($layout->getPatterns() as $pattern) {
            if (!$pattern->selectedMaterial) {
                return redirect()
                    ->route('layout.edit', $layout->layout_id)
                    ->withErrors(['msg' => 'Недостаточно данных для создания КП. Должны быть выбраны все соответствующие материалы'])
                ;
            }
        }
        
        $pdf = PDF::loadView(self::VIEV_SHOW, ['layout' => $layout]);
        return $pdf->setPaper('A4')->stream();
    }
}
