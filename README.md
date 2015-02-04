# Riesenia Utility Classes

[![Latest Version](https://img.shields.io/packagist/v/riesenia/utility.svg?style=flat-square)](https://packagist.org/packages/riesenia/utility)
[![Total Downloads](https://img.shields.io/packagist/dt/riesenia/utility.svg?style=flat-square)](https://packagist.org/packages/riesenia/utility)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This library provides a range of utility classes.

## Installation

Install the latest version using `composer require riesenia/utility`

Or add to your *composer.json* file as a requirement:

```json
{
    "require": {
        "riesenia/utility": "dev-master"
    }
}
```

## Kendo

Helpers simplifying usage of Kendo UI components.

### Table

```php
use Riesenia\Utility\Kendo\Table;

$table = Table::create('my_table_id');

// data source can be accessed directly
$table->dataSource->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);
$table->dataSource->addTransport('update', ['dataType' => 'json', 'url' => 'URL']);

// columns can have various types
$table->addColumn('name', 'Product name')
    ->addColumn('price', 'Product price', 'price')
    ->addColumn('active', 'Is active?', 'checkbox')
    ->addColumn('stock', 'Stock', 'number');

// you can use any custom column rendering class
// as long as it extends Riesenia\Utility\Kendo\Table\Column\Base
$table->addColumn('custom_field', 'Title', '\\Custom\\Rendering\\Class');

// html element (div)
echo $table;

// generated javascript
echo '<script>' . $table->script() . '</script>';
```