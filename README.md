# Riesenia Utility Classes

[![Build Status](https://img.shields.io/travis/riesenia/utility/master.svg?style=flat-square)](https://travis-ci.org/riesenia/utility)
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
        "riesenia/utility": "~1.0"
    }
}
```

## Kendo

Helpers simplifying usage of Kendo UI components.

### Table

```php
use Riesenia\Utility\Kendo\Table;

$table = Table::create('myTableId');

// datasource can be accessed directly
$table->dataSource->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);

// ... but addTransport can also be called directly on the table object
$table->addTransport('update', ['dataType' => 'json', 'url' => 'URL']);

// columns can have various types and additional options can be set
$table->addColumn('name', 'Product name')
    ->addColumn('price', 'Product price', 'price', ['class' => 'green'])
    ->addColumn('active', 'Is active?', 'checkbox')
    ->addColumn('stock', 'Stock', 'number');

// checkboxes for batch operations can be added
$table->addCheckboxes();

// class for table row can be added using 'addRowClass' method
$table->addRowClass('#: active ? "active" : "not-active" #');

// additional model options can be set using 'model' key
$table->addColumn('name', 'Product name', null, ['model' => ['editable' => false]]);

// you can use any custom column rendering class
// as long as it extends Riesenia\Utility\Kendo\Table\Column\Base
$table->addColumn('custom_field', 'Title', '\\Custom\\Rendering\\Class');

// link is built-in option
$table->addColumn('...', '...', '...', ['link' => 'URL']);

// any link attributes can be set
$table->addColumn('...', '...', '...', ['link' => ['href' => 'URL', 'title' => 'TITLE']]);

// the whole template can be overridden
$table->addColumn('...', '...', '...', ['template' => '# if (field) { # yes # } else { # no # } #']);

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

// generally used classes can be aliased, so previous example is equivalent to
Table::alias('alias_name', '\\Custom\\Action\\Class');
$table->addAction('alias_name');

// condition is built-in option
$table->addAction('...', ['condition' => 'count > 0']);

// set text for no results (will be added as first colspaned row)
$table->setNoResults('NO RESULTS!');

// html element (div)
echo $table;

// generated javascript
echo '<script>' . $table->script() . '</script>';
```

### Tree

```php
use Riesenia\Utility\Kendo\Tree;

$tree = Tree::create('myTreeId');

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

### Window

```php
use Riesenia\Utility\Kendo\Window;

$window = Window::create('myWindowId');

// html element (div)
echo $window;

// generated javascript
echo '<script>' . $window->script() . '</script>';

// method for opening window is automatically defined
echo '<script>myWindowIdOpen("WINDOW TITLE", "URL OF THE CONTENT TO LOAD");</script>';
```

### Tabber

```php
use Riesenia\Utility\Kendo\Tabber;

$tabber = Tabber::create('myTabberId');

// adding remote tab
$tabber->addRemoteTab('First tab', 'URL');

// active tab is set using third parameter
$tabber->addRemoteTab('Second tab', 'URL', true);

// html element (div & ul)
echo $tabber;

// generated javascript
echo '<script>' . $tabber->script() . '</script>';
```

### Upload

```php
use Riesenia\Utility\Kendo\Upload;

$upload = Upload::create('myUploadId');

// optionally set any attribute of the input
$upload->addAttribute('name', 'NAME');

// html element (input type file)
echo $upload;

// generated javascript
echo '<script>' . $upload->script() . '</script>';
```

### Select

```php
use Riesenia\Utility\Kendo\Select;

$select = Select::create('mySelectId');

// datasource can be accessed directly
$select->dataSource->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);

// ... but addTransport can also be called directly on the select object
$select->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);

// optionally set any attribute of the input
$select->addAttribute('name', 'NAME');

// html element (input)
echo $select;

// generated javascript
echo '<script>' . $select->script() . '</script>';
```

### ListView

```php
use Riesenia\Utility\Kendo\ListView;

$listView = ListView::create('myListViewId');

// datasource can be accessed directly
$listView->dataSource->addTransport('read', ['dataType' => 'json', 'url' => 'URL']);

// ... but addTransport can also be called directly on the listView object
$listView->addTransport('destroy', ['dataType' => 'json', 'url' => 'URL']);

// template can be set by id attribute
$listView->setTemplateById('ID');

// html element (div)
echo $listView;

// generated javascript
echo '<script>' . $listView->script() . '</script>';
```

### Date / DateTime

```php
use Riesenia\Utility\Kendo\DateTime;

$dateTime = DateTime::create('myDateTimeId');

// range can be set between two elements (two fields with ids 'from' and 'to')
DateTime::create('from')->rangeTo('to');
DateTime::create('to')->rangeFrom('from');

// optionally set any attribute of the input
$dateTime->addAttribute('name', 'NAME');

// html element (input)
// hidden input with same name is added automatically to provide MySQL datetime format
echo $dateTime;

// generated javascript
echo '<script>' . $dateTime->script() . '</script>';
