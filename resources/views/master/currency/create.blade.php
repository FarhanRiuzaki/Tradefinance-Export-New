{!! Form::open(array('route' => $route. '.store','method'=>'POST','id' => 'MyForm')) !!}

{{ Form::inputText('Kode Bank*',    'code',         null, null, ['placeholder' => 'masukan kode bank', 'required']) }}
{{ Form::inputText('Deskripsi*',    'description',  null, null, ['placeholder' => 'masukan deskripsi', 'required']) }}
{{ Form::inputText('ID BI',         'bi_id') }}
{{ Form::inputText('Kode GL',       'gl_code') }}

<button onclick="CheckValidation();" type="submit" id="btn-submit" class="btn font-weight-bold btn-block btn-primary">
        Submit
</button>

{!! Form::close() !!}
