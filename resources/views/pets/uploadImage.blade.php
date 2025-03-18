@extends('main')
@section('content')

     <form>
        <div class="mb-3 mt-3">
            <label class="form-label" for="additionalMetadata"><?= __('Additional metadata') ?>:</label>
            <textarea name="additionalMetadata" class="form-control" id="additionalMetadata"></textarea>
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label" for="file"><?= __('File') ?>:</label>
            <input type="file" name="file" class="form-control" id="file">
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
    </form> 

    <script>
        document.getElementsByTagName("form")[0]?.addEventListener("submit", function(event){
            event.preventDefault();
            save(new FormData(this));
        });

        function save(formData)
        {
            clearAlertBox();

            $.ajax({
                url: '{{ url('api/pet/' . $id . '/uploadImage') }}',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "X-API-KEY": '{{ config('app.api_key') }}'
                },
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (result) {
                    window.location = '{{ url('pet') }}';
                },
                error: function (result) {
                    displayRequestError(result.responseJSON);
                }
            });
        }
    </script>
    
@endsection