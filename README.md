ExpressionEngine all values plugin
=================================

With this plugin you can use all unique values of a specific custom channel field in your templates.
In case of a grid field type it uses all the rows from the field.

## Usage
```
{ exp:all_values:field field_name="field" }
	{ field }
{ /exp:all_values:field }
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
