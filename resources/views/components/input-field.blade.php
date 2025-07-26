<div class="mb-4">
    <label class="form-label" for="field-{{ $id }}">{{ $label }}
        @if(isset($required) && $required)
            <span class="text-danger">*</span>
        @endif
    </label>
    @if($type == 'textarea')
    <textarea type="{{ $type }}" class="form-control {{ $inputClass ?? '' }} {{ $errors->has($name) ? 'is-invalid' : '' }}" id="field-{{ $id }}" name="{{ $name }}" 
    placeholder="{{ $placeholder ?? '' }}">{{ $value ?? old($name) }}</textarea>
    @else
    <input type="{{ $type }}" class="form-control {{ $inputClass ?? '' }} {{ $errors->has($name) ? 'is-invalid' : '' }}" id="field-{{ $id }}" name="{{ $name }}" 
    placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? old($name) }}">
    @endif
    @if(!isset($isAjax) && $errors->has($name))
        <x-input-error :messages="$errors->get($name)" class="mt-2" />
    @else
        <div id="error-{{ $id }}" class="text-danger"></div>
    @endif
    @if($type == 'file' && !empty($value))
        <a href="{{ asset($value) }}" target="_blank" class="btn btn-primary mt-2">Lihat Dokumen</a>
    @endif
    
</div>