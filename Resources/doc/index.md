CrossKnowledgeDataTableBundle
================================

* [Installation](#installation)
* [Usage](#usage)
  - [Creating a table](#Creating a table)
  - [Rendering a table](#Rendering a table)
* [Examples](#Examples)
  - [Table with filter](#Creating a table)
  - [Unit testing a table](#Creating a table)
* [Contribute](#Contribute)
  - [Install frontend build](#Creating a table)
  - [Install composer & run phpunit](#Creating a table)

Installation
------------

### Step 1: Download the bundle

Open your command console, browse to your project and execute the following:

```sh
$ composer require crossknowledge/datatable-bundle
```

### Step 2: Enable the bundle

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new CrossKnowledge\DataTableBundle\CrossKnowledgeDataTableBundle(),
    );
}
```

### Step 3: Register the routing definition

```yaml
# app/config/routing.yml
crossknowledge_datatable:
    resource: "@CrossKnowledgeDataTableBundle/Resources/config/routing.yml"
    prefix:   /datatable
```

### Step 4: Publish assets
```sh
$ php app/console assets:install --symlink web
```

Usage
-----

Add these two lines in your layout:

```jinja
<link rel="stylesheet" href="{{ asset('/bundles/crossknowledgedatatable/vendor/datatables/media/css/jquery.dataTables.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('/bundles/crossknowledgedatatable/assets/css/ckdatable.css') }}"/>
<script src="{{ asset('/bundles/crossknowledgedatatable/vendor/datatables/media/js/jquery.dataTables.js') }}"/>
<script src="{{ asset('/bundles/crossknowledgedatatable/assets/js/ckdatable.js') }}"></script>
```
**Note:** if you are not using Twig, then it is no problem. What you need is to
add the two JavaScript files above loaded at some point in your web page, and optionnaly the custom styles.


### Creating a table

```php
<?php
namespace CrossKnowledge\DataTable\Table;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

/**
 * Represents the simplest datatable table
 *
 * @package CrossKnowledge\BOBundle\DataTable\Table
 */
class SimpleTable extends AbstractTable
{
    /**
     * @inheritdoc
     */
    public function buildColumns(ColumnBuilder $builder)
    {
        $authChecker = $this->authorizationChecker;

        $builder->add('FirstName', new Column('Firstname'))
                ->add('LastName', new Column('Name'));
    }
    /**
     * @inheritdoc
     */
    public function getUnfilteredCount()
    {
        return 2;
    }
    /**
     * @return \PropelCollection
     */
    public function getDataIterator()
    {
        return [
            ["FirstName" => "Foo", "LastName" => "Bar"],
            ["FirstName" => "Baguette", "LastName" => "Bread"],
        ]
    }
    /**
     * @return false this table do not use this feature
     */
    public function getFilteredCount()
    {
        return false;
    }
}
```

### Rendering a table

It's as simple as calling:

```jinja
{% if myTable.getUnfilteredCount()>0 %}
    {{ render_table(myTable) }} {# note: datatables.net also handle empty rows #}
{% else %}
    {{ 'No items found' | trans }}
{% endif %}

### Customizing table layout

To customize the layout  specify the layout by implementing AbstractTable::configureOptions(OptionsResolver $resolver)

```php
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'layout' => new CustomLayout('my-layout-id', "<'row'<'col-sm-6'l><'col-sm-6'f>>".
                                                         "<'row'<'col-sm-12'tr>>".
                                                         "<'row'<'col-sm-5'i><'col-sm-7'p>>");
        ]);
    }
```

CustomLayout is a class that the bundle provides, however it might better to extend it and implement your own
for reusability.

Datatables.net do not allow positionning filters anywhere. that's why the JS wrapper adds some magic and gives you
some more tools via magic classes.

for example, if you specify the class 'dom-position-filter-after' in the dom option of datatable (via the above layout class for example)
then the js wrapper will reposition the filters just after  the element holding the class 'dom-position-filter-after'

https://datatables.net/reference/option/dom

We might add later more magic classes if the needs occur.

Examples
--------

### Table with filter

```php
<?php
namespace CrossKnowledge\DataTable\Table;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

/**
 * Represents the simplest datatable table
 *
 * @package CrossKnowledge\BOBundle\DataTable\Table
 */
class FilterableTable extends AbstractTable
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'has_filter_form' => true
        ]);
    }
    /**
     * @inheritdoc
     */
    public function buildFilterForm(FormBuilder $builder)
    {
        $builder->add("status", "choice", [
            'choices' => [
                'status_all' => 'All',
                'not_started'=> 'Not started'
            ],
            'label' => 'Activity status:'
        ]);

        return $builder;
    }
    /**
     * @inheritdoc
     */
    public function buildColumns(ColumnBuilder $builder)
    {
        $builder->add('FirstName', new Column('Firstname'))
                ->add('LastName', new Column('Name'))
                ->add('status', (new Column('Activity status'))->setFormatValueCallback(function($value, array $row) {
                    if ($value=="" || $value=="not_started") {
                        return 'Not started';
                    }

                    return 'Started';
                }))

    }
    /**
     * @inheritdoc
     */
    public function getUnfilteredCount()
    {
        return 2;
    }

    public function getDataIterator()
    {
        $filterValue = $this->currentRequest->customFilter->get('status')->getData();
        if  ($filterValue=='not_started') {
            return [
                ["FirstName" => "Baguette", "LastName" => "Bread", "status" => "not_started"],
            ]
        } else {//All
            return [
                ["FirstName" => "Foo", "LastName" => "Bar", , "status" => "not_started"],
                ["FirstName" => "Baguette", "LastName" => "Bread", "status" => "all"],
            ]
        }
    }
    /**
     * @return false this table do not use this feature
     */
    public function getFilteredCount()
    {
        return $this->currentRequest->customFilter->get('status')->getData()=='not_started' ? 1 : 2;
    }
}
```

### Unit testing table with filter

Todo

Contribute
----------

### Install frontend build
todo

### Install composer & run phpunit
todo