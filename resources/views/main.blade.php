<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= __('eSprzedaż - zadanie rekrutacyjne') ?></title>

        {{-- <script src="<?= url('media/js/jquery.js') ?>"></script>
        <link href="<?= url('media/css/site/styles.css') ?>" rel="stylesheet"> --}}
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600;1,9..40,400&family=Libre+Franklin:ital,wght@0,400;0,600;1,400&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet"> --}}

    </head>
    <body class="">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('pet') }}"><?= __('Pets') ?></a>
                </li>
            </ul>
        </nav>
        <div class="container mt-3" id="alert-box"></div>
        <div class="container mt-5">
            @yield("content")
        </div>
        <script>
            function clearAlertBox()
            {
                $('#alert-box').html(null);
            }

            function displayRequestError(errorResponseJSON)
            {
                const div = document.createElement('div');
                div.className = 'alert alert-danger alert-dismissible';

                const button = document.createElement('button');
                button.className = 'btn-close';
                button.setAttribute('data-bs-dismiss', 'alert');

                const h4 = document.createElement('h4');
                h4.innerText = errorResponseJSON.code + ' ' + errorResponseJSON.message;

                div.appendChild(button);
                div.appendChild(h4);

                Object.values(errorResponseJSON.errors).forEach((fieldErrors) => {
                    const p = document.createElement('p');
                    p.innerText = fieldErrors.join(' ');
                    div.appendChild(p);
                });
                
                $('#alert-box').html(div);
            }
        </script>
    </body>
</html>
