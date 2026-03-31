<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @if(isset($page_js) && $page_js == 'datatable')
        <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    @endif

</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div style="width:250px;background:#343a40;min-height:100vh;">
        <h4 class="text-white p-3">Admin</h4>

        <ul class="nav flex-column">
            <li><a href="/dashboard" class="nav-link text-white">Dashboard</a></li>
            <li><a href="/products" class="nav-link text-white">Products</a></li>
            <li><a href="/users" class="nav-link text-white">Users</a></li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="flex-grow-1">

        <nav class="navbar bg-light px-3">
            <span>Welcome {{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger btn-sm">Logout</button>
            </form>
        </nav>

        <div class="container mt-3">
            @yield('content')
        </div>

    </div>

</div>

<!-- GLOBAL JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CONDITIONAL JS -->
@if(isset($page_js) && $page_js == 'datatable')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endif

@if(isset($page_js) && $page_js == 'validate')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endif

<!-- CSRF -->
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

@yield('scripts')

</body>
</html>