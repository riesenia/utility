<?php
namespace spec\Riesenia\Utility\Kendo;

use PhpSpec\ObjectBehavior;

class TableSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Riesenia\Utility\Kendo\Table');
    }

    public function it_creates_div()
    {
        $this->html()->shouldReturn('<div id="id"></div>');
    }

    public function it_can_add_transport_directly()
    {
        $this->addTransport('read', ['url' => 'URL'])->shouldReturn($this);
        $this->dataSource->getTransport()->shouldReturn(['read' => ['url' => 'URL']]);
    }

    public function it_can_set_row_class()
    {
        $this->addRowClass('#: active ? "active" : "not-active" #')->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="#: active ? "active" : "not-active" #" style=""></tr>');
    }

    public function it_can_set_row_style()
    {
        $this->addRowStyle('#: active ? "display: block" : "display: none" #')->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="" style="#: active ? "display: block" : "display: none" #"></tr>');
    }

    public function it_can_add_base_column()
    {
        $this->addColumn('name', 'Product name')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['name' => ['type' => 'string']]);
        $expected = $this->_expected('name', 'Product name', 'tableColumn', '#: name #');
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_column_with_link()
    {
        $this->addColumn('name', 'Product name', null, ['link' => 'URL'])->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="" style=""><td class="tableColumn" style="#: grid.columns[grid.element.find("th[data-field=name]").data("index")].hidden ? "display: none;" : "" #"># if (name !== null && name !== "") { #<a href="URL">#: name #</a># } else { # N/A # } #</td></tr>');
    }

    public function it_can_add_column_with_complex_link()
    {
        $this->addColumn('name', 'Product name', null, ['link' => ['href' => 'URL', 'target' => '_blank', 'title' => 'Link'], 'link_condition' => 'field'])->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="" style=""><td class="tableColumn" style="#: grid.columns[grid.element.find("th[data-field=name]").data("index")].hidden ? "display: none;" : "" #"># if (name !== null && name !== "") { ## if (field) { #<a href="URL" target="_blank" title="Link">#: name #</a># } else { # #: name # # } ## } else { # N/A # } #</td></tr>');
    }

    public function it_can_add_input_column()
    {
        $this->addColumn('name', 'Name', 'input')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['name' => ['type' => 'string']]);
        $expected = $this->_expected('name', 'Name', 'tableColumn tableInput', '# if (true) { #<input type="text" data-row-uid="#: uid #" name="nameInput" value="#: name #" /># } else { # N/A # } #', false);
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_numeric_input_column()
    {
        $this->addColumn('stock', 'Stock', 'input', ['model' => ['type' => 'number'], 'input' => ['type' => 'number', 'min' => 0]])->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['stock' => ['type' => 'number']]);
        $expected = $this->_expected('stock', 'Stock', 'tableColumn tableInput', '# if (true) { #<input type="number" data-row-uid="#: uid #" name="stockInput" value="#: stock #" min="0" /># } else { # N/A # } #', false);
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_checkbox_column()
    {
        $this->addColumn('active', 'Active', 'checkbox')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['active' => ['type' => 'boolean']]);
        $expected = $this->_expected('active', 'Active', 'tableColumn tableCheckbox', '<input type="checkbox" data-row-uid="#: uid #" name="activeInput" # if (active) { # checked="checked" # } # />', false);
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_date_column()
    {
        $this->addColumn('created', 'Created', 'date')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['created' => ['type' => 'date']]);
        $expected = $this->_expected('created', 'Created', 'tableColumn tableDate', '#: kendo.toString(created, "d") #');
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_datetime_column()
    {
        $this->addColumn('created', 'Created', 'datetime')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['created' => ['type' => 'date']]);
        $expected = $this->_expected('created', 'Created', 'tableColumn tableDate tableDatetime', '#: kendo.toString(created, "g") #');
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_enum_column()
    {
        $this->addColumn('type', 'Type', 'enum', ['options' => ['A' => 'Type A', 'B' => 'Type B']])->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['type' => ['type' => 'string']]);
        $expected = $this->_expected('type', 'Type', 'tableColumn', '# if (type.toString() == "A") { # Type A # } ## if (type.toString() == "B") { # Type B # } #', false);
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_number_column()
    {
        $this->addColumn('stock', 'Stock', 'number')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['stock' => ['type' => 'number']]);
        $expected = $this->_expected('stock', 'Stock', 'tableColumn tableNumber', '#: stock #');
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_price_column()
    {
        $this->addColumn('eur', 'Price', 'price')->shouldReturn($this);
        $this->model->getFields()->shouldReturn(['eur' => ['type' => 'number']]);
        $expected = $this->_expected('eur', 'Price', 'tableColumn tableNumber tablePrice', '#: kendo.toString(eur, "c2") #');
        $this->widget->getColumns()->shouldReturn($expected[0]);
        expect($this->_match_property("rowTemplate"))->toBe($expected[1]);
    }

    public function it_can_add_hierarchy_cell()
    {
        $this->setDetailInit('')->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="k-master-row" style=""><td class="k-hierarchy-cell" style=""><a class="k-icon k-plus" href="\\#" tabindex="-1"></a></td></tr>');
    }

    public function it_can_add_checkboxes()
    {
        $this->addCheckboxes()->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="" style=""><td class="tableColumn tableCheckbox tableCheckbox--main" style=""><input type="checkbox" value="#: id #" name="tableCheckbox" /></td></tr>');
    }

    public function it_can_add_action()
    {
        $this->addAction(null, ['icon' => 'music', 'link' => 'URL', 'title' => 'Play!'])->shouldReturn($this);
        $this->addAction(null, ['icon' => 'music', 'link' => 'URL', 'title' => 'Play 2!'])->shouldReturn($this);
        expect($this->_match_property("rowTemplate"))->toBe('# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="" style=""><td class="tableColumn tableActions" style="width: 90px;"><a class="btn btn-default " href="URL" title="Play!" target="_self"><span class="glyphicon glyphicon-music"></span></a> <a class="btn btn-default " href="URL" title="Play 2!" target="_self"><span class="glyphicon glyphicon-music"></span></a></td></tr>');
    }

    protected function _match_property($property)
    {
        return preg_match('/"' . $property . '":"(.+?)(?<!\\\\)"/', $this->script()->getWrappedObject(), $m) ? stripslashes($m[1]) : false;
    }

    protected function _expected($property, $title, $class, $td, $condition = true)
    {
        if ($condition) {
            $td = '# if (' . $property . ' !== null && ' . $property . ' !== "") { #' . $td . '# } else { # N/A # } #';
        }

        return [
            [['field' => $property, 'title' => $title, 'class' => $class, 'style' => '#: grid.columns[grid.element.find("th[data-field=' . $property . ']").data("index")].hidden ? "display: none;" : "" #', 'headerAttributes' => ['class' => $class, 'style' => '#: grid.columns[grid.element.find("th[data-field=' . $property . ']").data("index")].hidden ? "display: none;" : "" #']]],
            '# var grid = $("\#id").data("kendoGrid"); #<tr data-uid="#: uid #" class="" style=""><td class="' . $class . '" style="#: grid.columns[grid.element.find("th[data-field=' . $property . ']").data("index")].hidden ? "display: none;" : "" #">' . $td . '</td></tr>'
        ];
    }
}
