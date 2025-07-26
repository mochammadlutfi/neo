@props([
    'placeholder' => 'Pilih',
    'id' => '',
    'label' => '',
    'name' => '',
    'options' => [],
    'value' => '',
    'disabled' => false,
])
<div class="mb-4">
    <label class="form-label" for="field-{{ $id }}">{{ $label }}
        @if(isset($required) && $required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}" id="field-{{ $id }}" name="{{ $name }}" 
        {{ (isset($disabled) && $disabled) ? 'disabled="disabled"' : '' }}>
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $opt)
            <option value="{{ $opt['value'] }}" {{ (old($name, ($value ?? '')) == $opt['value']) ? 'selected' : '' }}>{{ $opt['label'] }}</option>
        @endforeach
    </select>
    @if(!isset($isAjax) && $errors->has($name))
        <x-input-error :messages="$errors->get($name)" class="mt-2" />
    @else
        <div id="error-{{ $id }}" class="text-danger"></div>
    @endif
    
</div>