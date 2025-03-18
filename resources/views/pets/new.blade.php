@extends('main')
@section('content')

     <form>
        <div class="mb-3 mt-3">
            <label class="form-label" for="name"><?= __('Name') ?>:</label>
            <input type="text" class="form-control" id="name">
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label" for="category"><?= __('Category') ?>:</label>
            <select class="form-control" id="category">
                <? foreach($categories as $category): ?>
                    <option value="<?= $category->id ?>"><?= $category->name ?></option>
                <? endforeach ?>
            </select>
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label" for="status"><?= __('Status') ?>:</label>
            <select class="form-control" id="status">
                <? foreach($petStatuses as $petStatus): ?>
                    <option value="<?= $petStatus->value ?>"><?= $petStatus->value ?></option>
                <? endforeach ?>
            </select>
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label" for="tags"><?= __('Tags') ?>:</label>
            <select class="form-control" id="tags" multiple>
                <? foreach($tags as $tag): ?>
                    <option value="<?= $tag->id ?>"><?= $tag->name ?></option>
                <? endforeach ?>
            </select>
        </div>
        <button id="submit" type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
    </form> 

    <script>
        document.getElementById("submit").addEventListener("click", function(event){
            event.preventDefault();
            save();
        }); 

        function save()
        {
            clearAlertBox();

            $.ajax({
                url: '{{ url('api/pet') }}',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "X-API-KEY": '{{ config('app.api_key') }}'
                },
                data: JSON.stringify({
                    "name": $('#name').val(),
                    "category": {
                        "id": $('#category').val()
                    },
                    "status": $('#status').val(),
                    "tags": getTags()
                }),
                dataType: 'json',
                contentType: 'application/json',
                type: 'POST',
                success: function (result) {
                    window.location = '{{ url('pet') }}';
                },
                error: function (result) {
                    displayRequestError(result.responseJSON);
                }
            });
        }

        function getTags()
        {
            const tags = [];
            $('#tags').val().forEach(element => {
                tags.push({"id": element});
            });
            return tags;
        }
    </script>
    
@endsection