{!! Form::open(array('route' => $route. '.store','method'=>'POST','id' => 'MyForm')) !!}

{{ Form::inputNumber('Code*',       'code', null, null, ['required']) }}
{{ Form::inputText('Description*',  'name', null, null, ['required'] ) }}
<div class="checkbox-list">
<label class="checkbox">
    <input type="checkbox" checked="checked" name="status"/>
    <span></span>
    Mandatory
</label>
</div>
<br>
<button onclick="CheckValidation();" type="submit" id="btn-submit" class="btn font-weight-bold btn-block btn-primary">
    Submit
</button>

{!! Form::close() !!}
