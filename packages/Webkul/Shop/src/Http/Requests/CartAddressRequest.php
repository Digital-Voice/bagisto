<?php

namespace Webkul\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Webkul\Core\Rules\PhoneNumber;

class CartAddressRequest extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Determine if the product is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->has('billing')) {
            $this->mergeAddressRules('billing');
        }

        if (! $this->input('billing.use_for_shipping')) {
            $this->mergeAddressRules('shipping');
        }

        return $this->rules;
    }

    /**
     * Merge new address rules.
     *
     * @return void
     */
    private function mergeAddressRules(string $addressType)
    {
        if (core()->getConfigData('customer.address.information.split-name')) {
            $this->mergeWithRules([
                "{$addressType}.full_name" => ["required"],
            ]);
        }

        $this->mergeWithRules([
            "{$addressType}.company_name" => ['nullable'],
            "{$addressType}.first_name"   => ['required'],
            "{$addressType}.last_name"    => ['required'],
            "{$addressType}.email"        => ['nullable'],
            "{$addressType}.address"      => ['required', 'array', 'min:1'],
            "{$addressType}.city"         => ['required'],
            "{$addressType}.country"      => core()->isCountryRequired() ? ['required'] : ['nullable'],
            "{$addressType}.state"        => core()->isStateRequired() ? ['required'] : ['nullable'],
            "{$addressType}.postcode"     => core()->isPostCodeRequired() ? ['required', 'numeric'] : ['nullable', 'numeric'],
            "{$addressType}.phone"        => ['required', new PhoneNumber],
        ]);
    }

    /**
     * Merge additional rules.
     */
    private function mergeWithRules($rules): void
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    protected function prepareForValidation()
    {
        if (! core()->getConfigData('customer.address.information.split-name')) {
            foreach (Arr::get($this->billing, 'use_for_shipping') ? ['billing'] : ['billing', 'shipping'] as $type) {
                $parts = explode(' ', $fullName = Arr::get($this->$type, 'full_name'));

                $this->merge([$type => array_merge($this->$type, [
                    'last_name' => count($parts) == 1 ? $fullName : array_pop($parts),
                    'first_name' => count($parts) == 1 ? '-' : implode(' ', $parts),
                ])]);
            }
        }

        if (! core()->getConfigData('customer.address.display.city')) {
            $this->merge(['billing' => array_merge($this->billing, ['city' => '-'])]);

            if (! Arr::get($this->billing, 'use_for_shipping')) {
                $this->merge(['shipping' => array_merge($this->shipping, ['city' => '-'])]);
            }
        }
    }
}
