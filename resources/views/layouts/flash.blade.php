@if($errors && (count($errors) > 0))
    <div class="alert alert-danger" style="margin-top: 5px">
        <strong>Attention</strong> Certains champs n'ont pas été correctement remplis
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::has('success'))
    <div class="alert alert-success" style="margin-top: 5px">
        {{ Session::get('success') }}
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger" style="margin-top: 5px">
        {{ Session::get('error') }}
    </div>
@endif
