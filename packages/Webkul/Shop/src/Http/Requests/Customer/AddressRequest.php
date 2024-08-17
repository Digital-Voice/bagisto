<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Customer\Rules\VatIdRule;

class AddressRequest extends FormRequest
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
        $rules = [
            'company_name' => ['nullable'],
            'first_name'   => ['required'],
            'last_name'    => ['required'],
            'address'      => ['required', 'array', 'min:1'],
            'country'      => core()->isCountryRequired() ? ['required'] : ['nullable'],
            'state'        => core()->isStateRequired() ? ['required'] : ['nullable'],
            'city'         => ['required', 'string'],
            'postcode'     => core()->isPostCodeRequired() ? ['required', 'numeric'] : ['numeric'],
            'phone'        => ['required', new PhoneNumber],
            'vat_id'       => [new VatIdRule],
            'email'        => ['nullable'],
        ];

        if (core()->getConfigData('customer.address.information.split-name')) {
            return array_merge($rules, [
                'full_name' => ['required'],
            ]);
        }

        return $rules;
    }

    /**
     * Attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'address.*' => 'address',
        ];
    }

    protected function prepareForValidation()
    {
        if (! core()->getConfigData('customer.address.information.split-name') && $this->full_name) {
            if (count($parts = explode(' ', $this->full_name)) == 1) {
                $this->merge(['first_name' => $this->full_name, 'last_name' => '-']);
            } else {
                $this->merge(['last_name' => array_pop($parts), 'first_name' => implode(' ', $parts)]);
            }
        }

        if (! core()->getConfigData('customer.address.display.city')) {
            $this->merge(['city' => '-']);
        }
    }
}
