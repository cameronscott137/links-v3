<?php

namespace Statamic\Addons\FieldOptions;

use Statamic\API\Fieldset;
use Statamic\API\Parse;
use Statamic\Extend\Tags;

class FieldOptionsTags extends Tags
{
    public function index()
    {
        // Get fieldset from parameter or front-matter
        // Bail and log error if no fieldset
        if ( ! $fieldset = $this->get('fieldset', array_get($this->context, 'fieldset'))) {
            \Log::error('Fieldset not found in front-matter or in parameters.');
            return false;
        }

        // Get the field config
        $field = $this->get('field');
        $fieldset = Fieldset::get($fieldset);
        $fieldConfig = array_get($fieldset->contents(), "fields:$field");

        // Reformat the options array
        if ($options = array_get($fieldConfig, 'options')) {
            foreach ($options as $key => $val) {
                $fieldConfig['options'][] = array(
                    'key'  => $key,
                    'value' => $val
                );
                unset($fieldConfig['options'][$key]);
            }
        }

        return Parse::templateLoop($this->content, $fieldConfig['options'], true, $this->context);
    }
}
