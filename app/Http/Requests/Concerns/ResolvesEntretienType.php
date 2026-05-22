<?php

namespace App\Http\Requests\Concerns;

use App\Models\EntretienType;
use Illuminate\Validation\Rule;

trait ResolvesEntretienType
{
    protected function prepareEntretienType(): void
    {
        if ($this->input('type') === '__new__' && $this->filled('type_custom')) {
            $type = EntretienType::findOrCreateForUser(
                $this->user(),
                $this->string('type_custom')->toString()
            );
            $this->merge(['type' => $type->slug]);
        }
    }

    /** @return array<string, mixed> */
    protected function entretienTypeRules(): array
    {
        return [
            'type'        => ['required', Rule::in(EntretienType::slugsForUser($this->user()))],
            'type_custom' => 'required_if:type,__new__|nullable|string|max:100',
        ];
    }

    /** @return array<string, mixed> */
    protected function entretienDateHeureRules(): array
    {
        return [
            'date_heure' => ['required', 'date', 'after_or_equal:now'],
        ];
    }
}
