ExpressionEngine all values plugin
=================================

With this plugin you can use all unique values of a specific custom channel field in your templates.
In case of a grid field type it uses all the rows from the field.

## Usage
```
&#123;exp:all_values:field field_name="field"&#125;
	&#123;field&#125;
&#123;/exp:all_values:field&#125;
```

## Parameters
### field_name=
(required) - Use the short name of your custom field

### limit=
(optional) - Default:100

### offset=
(optional) - Default:0

### orderby=
(optional) - Default: field_name, Default (grid): row_order

### sort=
(optional) - asc or desc. Default:asc
