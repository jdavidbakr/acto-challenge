<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name'=>'required',
            'cards'=>'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cards = collect(explode(" ", $this->cards));
            $this->checkCardData($validator, $cards);
            $this->checkCardDuplicates($validator, $cards);
        });
    }

    protected function checkCardData($validator, $cards)
    {
        $cards->each(function ($card) use ($validator) {
            if (!in_array($card, config('cards'))) {
                if ($card) {
                    $validator->errors()->add('cards', $card.' is not a valid card');
                }
            }
        });
    }

    protected function checkCardDuplicates($validator, $cards)
    {
        if ($cards->transform(function ($card) {
            return (object)[
                'value'=>$card
            ];
        })->groupBy('value')
        ->filter(function ($cardGroup) {
            return $cardGroup->count() > 1;
        })->count()
        ) {
            $validator->errors()->add('cards', 'You may not enter duplicate cards');
        }
    }
}
