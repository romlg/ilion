<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return auth()->check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2|max:255',
            'calculate_co' => 'boolean',
            'material_to_pattern_material' => 'nullable|array',
            'material_to_pattern_material.*' => 'nullable|integer|required_unless:calculate_co,false,null,0,|exists:materials,material_id'
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'material_to_pattern_material.*.required_unless' => 'Недостаточно данных для создания КП. Должны быть выбраны все соответствующие материалы'
        ];
    }
    
    /**
     * 
     * @return array
     */
    public function getMaterialsForPatterns(): array
    {
        return $this->get('material_to_pattern_material');
    }
    
    /**
     * 
     * @return bool
     */
    public function isCO(): bool
    {
        return filter_var($this->get('calculate_co'), FILTER_VALIDATE_BOOLEAN);
    }
}
