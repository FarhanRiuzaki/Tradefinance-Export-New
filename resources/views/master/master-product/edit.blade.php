{!! Form::model($edit, ['method' => 'PATCH','route' => [$route.'.update', Crypt::encrypt($edit->id)], 'id' => 'MyForm']) !!}

{{ Form::inputText('Kode Product*', 'code', null, null, ['maxlength'=> 15, 'required']) }}
{{ Form::inputText('Nama Product*', 'name', null, null, ['required']) }}

<button onclick="CheckValidation();" type="submit" id="btn-submit" class="btn font-weight-bold btn-block btn-primary">
        Submit
</button>

{!! Form::close() !!}
