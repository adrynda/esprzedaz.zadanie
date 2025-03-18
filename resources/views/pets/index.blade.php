@extends('main')
@section('content')

    <table class="table table-striped table-sm table-hover table-responsive">
        <thead>
            <tr class="table-dark">
                <th><?= __('Name') ?></th>
                <th><?= __('Category') ?></th>
                <th><?= __('Status') ?></th>
                <th class="w-25 text-center">
                    <a class="btn btn-success" href="{{ url('pet/new') }}"><?= __('Add') ?></a>
                </th>
            </tr>
        </thead>
        <tbody id="list"></tbody>
    </table>

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
            tr.className = 'w-100';
            tr.appendChild(prepareTd(pet.name));
            tr.appendChild(prepareTd(pet.category.name));
            tr.appendChild(prepareTd(pet.status));
            tr.appendChild(prepareButtons(pet));
            return tr;
        }

        function prepareTd(value)
        {
            const td = document.createElement('td');
            td.innerText = value;
            return td;
        }

        function prepareButtons(pet)
        {
            const div = document.createElement('div');
            div.className = 'btn-group-vertical';
            div.appendChild(prepareLink('btn btn-outline-primary', '<?= __('Edit') ?>', 'pet/' + pet.id));
            div.appendChild(prepareLink('btn btn-outline-info', '<?= __('Upload image') ?>', 'pet/' + pet.id + '/uploadImage'));
            div.appendChild(prepareLink('btn btn-outline-warning', '<?= __('Custom update') ?>', 'pet/' + pet.id + '/customUpdate'));
            div.appendChild(prepareDeleteButton(pet.id));

            const td = document.createElement('td');
            td.className = 'text-center';
            td.appendChild(div);

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
            button.className = 'btn btn-outline-danger';
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
    </script>
    
@endsection