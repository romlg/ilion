@if($errors->any())
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                {{ $errors->first() }}
            </div>
        </div>
    </div>
@endif