<?php

namespace App\Http\Requests;

use App\Rules\Cpf;
use App\Services\ParseFormData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PessoaRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data = $this->all();

        if (isset($data['cpf'])) {
            data_set($data, 'cpf', ParseFormData::clearString($data['cpf']));
        }

        if($this->method() === 'POST' && isset($data['telefones'])) {
            for ($i = 0; $i < count($data['telefones']); $i++) {
                data_set($data, 'telefones.' . $i, ParseFormData::clearString($data['telefones'][$i]));
            }
        }

        $this->merge($data);

    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        if($this->method() === 'POST') {
            $rules = [
                'nome'                          => 'required|min:3|max:100',
                'cpf'                           => ['required','digits:11', new Cpf, Rule::unique('pessoas', 'cpf')->ignore($this->id, 'id')],
                'email'                         => ['nullable','min:3','max:60','email'],
                'data_nasc'                     => 'required|date|date_format:Y-m-d|before:now',
                'nacionalidade'                 => 'required|min:3|max:40',
                'telefones.*.numero'            => 'nullable|digits_between:10,11',
            ];
        }

        if($this->method() === 'PUT'){
            $rules = [
                'nome'                          => 'nullable|min:3|max:100',
                'cpf'                           => ['nullable','digits:11', new Cpf, Rule::unique('pessoas', 'cpf')->ignore($this->id, 'id')],
                'email'                         => ['nullable','min:3','max:60','email'],
                'data_nasc'                     => 'nullable|date|date_format:Y-m-d|before:now',
                'nacionalidade'                 => 'nullable|min:3|max:40'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nome.required'                                  => "O campo Nome é obrigatório",
            'nome.min'                                       => "O Nome precisa ter no mínimo :min caracteres",
            'nome.max'                                       => "O Nome precisa ter no máximo :max caracteres",
            'email.unique'                                   => "Email já cadastrado",
            'email.min'                                      => "O Email precisa ter no mínimo :min caracteres",
            'email.max'                                      => "O Email precisa ter no máximo :max caracteres",
            'email.email'                                    => "Email inválido",
            'cpf.required'                                   => "O CPF é obrigatório",
            'cpf.digits'                                     => "O tamanho do CPF é inválido",
            'cpf.unique'                                     => "CPF já cadastrado",
            'data_nasc.required'                             => "O campo Data de Nascimento é obrigatório",
            'data_nasc.date'                                 => "O campo Data de Nascimento é inválido",
            'data_nasc.date_format'                          => "O formato da Data de Nascimento é inválido",
            'data_nasc.before'                               => "O campo Data de Nascimento é inválido",
            'nacionalidade.required'                         => "O campo Nacionalidade é obrigatório",
            'nacionalidade.min'                              => "O campo Nacionalidade precisa ter no mínimo :min caracteres",
            'nacionalidade.max'                              => "O campo Nacionalidade precisa ter no máximo :max caracteres",
            'telefones.*.numero.digits_between'              => "Telefone inválido",
        ];
    }
}
