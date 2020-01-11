<?php

use Thera\BootForm\BootForm;

class BootFormTest extends PHPUnit_Framework_TestCase
{
    protected $bootForm;

    protected $htmlBuilderMock;

    protected $formBuidlerMock;

    protected $configMock;

    protected $sessionMock;

    public function setUp()
    {
        $this->htmlBuilderMock = Mockery::mock('Collective\Html\HtmlBuilder');
        $this->formBuidlerMock = Mockery::mock('Collective\Html\FormBuilder');
        $this->configMock = Mockery::mock('Illuminate\Contracts\Config\Repository')->shouldDeferMissing();
        $this->sessionMock = Mockery::mock('Illuminate\Session\SessionManager')->shouldDeferMissing();

        $this->bootForm = new BootForm(
            $this->htmlBuilderMock,
            $this->formBuidlerMock,
            $this->configMock,
            $this->sessionMock
        );
    }

    /** @test */
    public function it_opens_default_form()
    {
        $this->formBuidlerMock->shouldReceive('open')->once()->with([
            'role' => 'form',
            'class' => 'form-horizontal'
        ])->andReturn('foo');

        $this->configMock->shouldReceive('get')
            ->with('boot_form.type')
            ->once()
            ->andReturn('form-horizontal');

        $result = $this->bootForm->open();

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_opens_store_model_form()
    {
        $model = Mockery::mock('Illuminate\Database\Eloquent\Model');
        $model->exists = false;

        $this->formBuidlerMock->shouldReceive('model')
            ->once()
            ->with($model, [
                'role' => 'form',
                'route' => 'bar',
                'method' => 'POST',
                'class' => 'form-horizontal',
            ])
            ->andReturn('foo');

        $this->configMock->shouldReceive('get')
            ->with('boot_form.type')
            ->once()
            ->andReturn('form-horizontal');

        $result = $this->bootForm->open([
            'model' => $model,
            'store' => 'bar',
            'update' => 'baz'
        ]);

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_opens_update_model_form()
    {
        $model = Mockery::mock('Illuminate\Database\Eloquent\Model');
        $model->exists = true;

        $model->shouldReceive('getRouteKey')
            ->once()
            ->andReturn(1);

        $this->formBuidlerMock->shouldReceive('model')
            ->once()
            ->with($model, [
                'role' => 'form',
                'route' => ['baz', 1],
                'method' => 'PUT',
                'class' => 'form-horizontal',
            ])
            ->andReturn('foo');

        $this->configMock->shouldReceive('get')
            ->with('boot_form.type')
            ->once()
            ->andReturn('form-horizontal');

        $result = $this->bootForm->open([
            'model' => $model,
            'store' => 'bar',
            'update' => 'baz'
        ]);

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_opens_a_vertical_form()
    {
        $this->formBuidlerMock->shouldReceive('open')
            ->with([
                'role' => 'form',
                'class' => '',
            ])
            ->once()
            ->andReturn('foo');

        $result = $this->bootForm->vertical();

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_opens_an_inline_form()
    {
        $this->formBuidlerMock->shouldReceive('open')
            ->with([
                'class' => 'form-inline',
                'role' => 'form'
            ])
            ->once()
            ->andReturn('foo');

        $result = $this->bootForm->inline();

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_opens_a_horizontal_form()
    {
        $this->formBuidlerMock->shouldReceive('open')
            ->with([
                'class' => 'form-horizontal',
                'role' => 'form'
            ])
            ->once()
            ->andReturn('foo');

        $result = $this->bootForm->horizontal();

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_closes_a_form()
    {
        $this->formBuidlerMock->shouldReceive('close')->once()->andReturn('foo');

        $result = $this->bootForm->close();

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_returns_normal_field_names()
    {
        $result = $this->bootForm->flattenFieldName('foo');

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_removes_empty_array_from_field_name()
    {
        $result = $this->bootForm->flattenFieldName('foo[]');

        $this->assertEquals('foo', $result);
    }

    /** @test */
    public function it_flattens_array_from_field_name()
    {
        $result = $this->bootForm->flattenFieldName('foo[bar]');

        $this->assertEquals('foo.bar', $result);
    }
    
    /** @test */
    public function in_allows_zero_in_field_name()
    {
        $result = $this->bootForm->flattenFieldName('foo[0]');

        $this->assertEquals('foo.0', $result);
    }

    /** @test */
    public function it_flattens_nested_array_from_field_name()
    {
        $result = $this->bootForm->flattenFieldName('foo[bar][baz]');

        $this->assertEquals('foo.bar.baz', $result);
    }
}