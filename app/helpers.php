<?php

if (!function_exists('localize')) {
    function localize($model, $field)
    {
        if (!$model) return '';
        $locale = App::getLocale();
        if ($locale === 'fa') return $model->$field ?? '';
        $localized = $model->{$field . '_' . $locale};
        return $localized ?? $model->$field ?? '';
    }
}
