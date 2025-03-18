@extends('main')
@section('content')

     <form>
        <div class="mb-3 mt-3">
            <label class="form-label" for="name"><?= __('Name') ?>:</label>
            <input type="text" name="name" class="form-control" id="name">
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label" for="status"><?= __('Status') ?>:</label>
            <select name="status" class="form-control" id="status">
                <? foreach($petStatuses as $petStatus): ?>
                    <option value="<?= $petStatus->value ?>"><?= $petStatus->value ?></option>
                <? endforeach ?>
            </select>
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
    </form> 

    <script>
        loadPet();
        
        document.getElementsByTagName("form")[0]?.addEventListener("submit", function(event){
            event.preventDefault();
            save(new FormData(this));
        });

        function loadPet()
        {
            $.ajax({
                url: '{{ url('api/pet/' . $id) }}',
                type: 'GET',
                success: function (result) {
                    $('#name').val(result.name);
                    $('#status').val(result.status);
                },
                error: function (result) {
                    displayRequestError(result.responseJSON);
                }
            });
        }

        function save(formData)
        {
            $.ajax({
                url: '{{ url('api/pet/' . $id) }}',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
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

        function displayRequestError(errorResponseJSON)
        {
            // gdybym miał więcej czasu podświetliłbym pola
            alert(errorResponseJSON.message);
        }
    </script>
    
@endsection