@extends('main')
@section('content')

     <form>
        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd">
        </div>
        <div class="checkbox">
            <label><input type="checkbox"> Remember me</label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form> 

    <script>
        getList();
        
        function getList()
        {
            $.get(
                'api/pet/findByStatus?status=available',
                function (data, status) {
                    loadList(data);
                }
            );
        }

        function loadList(pets)
        {
            $('tbody#list').html(null);
            pets.forEach(function (pet) {
                $('tbody#list').append(prepareRowHtml(pet));
            });
        }

        function prepareRowHtml(pet)
        {
            const tr = document.createElement('tr');
            
            tr.appendChild(prepareTd(pet.name));
            tr.appendChild(prepareTd(pet.id));
            tr.appendChild(prepareTd(pet.status));
            tr.appendChild(prepareButtons(pet));
            return tr;
        }

        function prepareTd(value)
        {
            const td = document.createElement('td');
            // td.appendChild(value);
            td.innerText = value;
            return td;
        }

        function prepareButtons(pet)
        {
            const td = document.createElement('td');
            td.appendChild(prepareLink('btn btn-warning', '<?= __('Edit') ?>', 'pet/' + pet.id));
            td.appendChild(prepareLink('btn btn-warning', '<?= __('Upload image') ?>', 'pet/' + pet.id + '/uploadImage'));
            td.appendChild(prepareLink('btn btn-warning', '<?= __('Custom update') ?>', 'pet/' + pet.id + '/customUpdate'));
            td.appendChild(prepareDeleteButton(pet.id));
            return td;
        }

        function prepareLink(cssClasses, label, url)
        {
            const a = document.createElement('a');
            a.className = cssClasses;
            a.innerText = label;
            a.href = url;
            return a;
        }

        function prepareDeleteButton(id)
        {
            const button = document.createElement('button');
            button.className = 'btn btn-danger';
            button.innerText = '<?= __('Delete') ?>';
            button.onclick = function () {
                remove(this.dataset.id);
            };
            button.dataset.id = id;
            return button;
        }

        function remove(id)
        {
            $.ajax({
                url: 'api/pet/' + id,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                type: 'DELETE',
                success: function (result) {
                    getList();
                },
                error: function (result) {
                    displayRequestError(result.responseJSON);
                }
            });
        }

        function displayRequestError(errorResponseJSON)
        {
            // gdybym miał więcej czasu użyłbym BS5 modala
            alert(errorResponseJSON.message);
        }
    </script>
    
@endsection