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

// datasource can be accessed directly
$table->dataSource->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);

// ... but addTransport can also be called directly on the table object
$table->addTransport('update', ['dataType' => 'json', 'url' => 'URL']);

// columns can have various types and additional options can be set
$table->addColumn('name', 'Product name')
    ->addColumn('price', 'Product price', 'price', ['class' => 'green'])
    ->addColumn('active', 'Is active?', 'checkbox')
    ->addColumn('stock', 'Stock', 'number');

// additional model options can be set using 'model' key
$table->addColumn('name', 'Product name', null, ['model' => ['editable' => false]]);

// you can use any custom column rendering class
// as long as it extends Riesenia\Utility\Kendo\Table\Column\Base
$table->addColumn('custom_field', 'Title', '\\Custom\\Rendering\\Class');

// actions are usually icons with links
// icons are bootstrap classes without glyphicon prefix
$table->addAction(null, [
    'icon' => 'music',
    'link' => 'URL',
    'title' => 'Play!'
]);

// or predifined edit or delete operation
$table->addAction('delete');

// you can use any custom column action class
// as long as it extends Riesenia\Utility\Kendo\Table\Action\Base
$table->addAction('\\Custom\\Action\\Class');

// html element (div)
echo $table;

// generated javascript
echo '<script>' . $table->script() . '</script>';
```

### Tree

```php
use Riesenia\Utility\Kendo\Tree;

$tree = Tree::create('my_tree_id');

// datasource can be accessed directly
$tree->dataSource->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);

// ... but addTransport can also be called directly on the tree object
$tree->addTransport('delete', ['dataType' => 'json', 'url' => 'URL']);

// add hasChildren field (to allow tree expanding), default field name is 'hasChildren'
// but any field name can be passed (do not use 'children' as field name)
$tree->hasChildren();

// html element (div)
echo $tree;

// generated javascript
echo '<script>' . $tree->script() . '</script>';
```
