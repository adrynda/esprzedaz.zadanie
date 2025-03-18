@extends('main')
@section('content')

     <form>
        <div class="mb-3 mt-3">
            <label class="form-label" for="name"><?= __('Name') ?>:</label>
            <input type="text" class="form-control" id="name">
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label" for="status"><?= __('Status') ?>:</label>
            <select class="form-control" id="status">
                <? foreach($petStatuses as $petStatus): ?>
                    <option value="<?= $petStatus->value ?>"><?= $petStatus->value ?></option>
                <? endforeach ?>
            </select>
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
    </form> 

    <script>
        loadPet();
        
        document.getElementById("submit").addEventListener("click", function(event){
            event.preventDefault();
            save();
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

        function save()
        {
            $.ajax({
                url: '{{ url('api/pet/' . $id) }}',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    "name": $('#name').val(),
                    "status": $('#status').val()
                },
                type: 'POST',
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