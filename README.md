CrossKnowledge DataTable Bundle
===============================

The CrossKnowledge/DataTableBundle aims to integrates datatables.net into Symfony regardless of the data format/ORM(Propel/Doctrine)

Features:

- Table creation with an OO API (ColumnBuilder, ColumnTypes, OptionResolver managed options per table & per Column types)
- Filters via Symfony forms (implement: buildFilterForm(FormBuilder $builder) from AbstractTable)
- Custom datatables.net layouts via an OO API (see: DataTable/Table/Element/Layout/Boostrap.php)
- Automatic server side (service tagging)
- An easy way to  specify any option that datables.net JS Api can handle
- An object oriented Javascript bridge between datatables.net and the PHP object model. written in ES6 (via gulp/babel).
- Easily unit testable tables
- Unit tested at ~90%

Dependencies not listed in composer.json / bower.json :

- jquery must be available and compatible with datatables.net (jquery >=1.7.0 for datatables 1.10.2 for instance)

[![Build Status](https://travis-ci.org/CrossKnowledge/DataTableBundle.svg?branch=master)](https://travis-ci.org/CrossKnowledge/DataTableBundle)[![Total Downloads](https://poser.pugx.org/crossknowledge/datatable-bundle/downloads.svg)](https://packagist.org/packages/crossknowledge/datatable-bundle) [![Latest Stable Version](https://poser.pugx.org/crossknowledge/datatable-bundle/v/stable.svg)](https://packagist.org/packages/crossknowledge/datatable-bundle)

Documentation
-------------

The source of the documentation is stored in the `Resources/doc/` folder

[Read the Documentation for master](https://github.com/CrossKnowledge/DataTableBundle/blob/master/Resources/doc/index.md)

Installation
------------

All the installation instructions are located in the documentation.

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

CrossKnowledgeDataTableBundle is a [CrossKnowledge](https://crossknowledge.com) initiative.
See also the list of [contributors](https://github.com/CrossKnowledge/DataTableBundle/contributors).
A couple of "distribution" (travis,readme.md, etc..) files are inspired from FriendsOfSymfony/FOSUserBundle's.

Contributions
-------------

Contributions are more than welcome.
We will try to integrate them. As long as there is no BC, anything can be suggested.


Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/CrossKnowledge/DataTableBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
