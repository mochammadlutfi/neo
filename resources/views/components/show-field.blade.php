@props([
    'label' => '',
    'value' => '',
    'id' => '',
])
<div class="row mb-2" id="dt-{{ $id }}">
    <label class="col-sm-4 fw-medium">{{ $label }}</label>
    <div class="col-sm-8">
        : <span class="value">{{ $value }}</span>
    </div>
</div>