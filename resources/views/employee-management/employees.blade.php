@extends('../layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item ">
    <a href="{{ route('employees.list', ['siteId' => $site->id]) }}">{{
        $site->site_name }}</a>
</li>
@endforeach
@endpush

@section('page-content')
    @livewire('employee-management.employee-list')
@endsection


@push('jq-code')
    setTimeout(function() {
        $('.alert-success').fadeOut(5000);
    }, 5000);
    setTimeout(function() {
        $('.alert-error').fadeOut(5000);
    }, 5000);
    setTimeout(function() {
        $('.alert-danger').fadeOut(5000);
    }, 5000);
        
@endpush

@push('js-code')
    document.addEventListener('DOMContentLoaded', () => {
        const openFormButton = document.getElementById('openForm');
        const addFormTag = document.getElementById('addForm');
        const fileForm = document.getElementById('fileForm');
        const fileFormSubmit = document.getElementById('fileFormSubmit');
        const uploadForm = document.getElementById('uploadForm');
        
        openFormButton.addEventListener('click', () => {
            openFormButton.style.display = 'none';
            addFormTag.classList.add('d-none');
            fileForm.classList.remove('d-none');
        });

        fileFormSubmit.addEventListener('click', (event) => {
        const fileInput = fileForm.querySelector('input[type="file"]');
        if (!fileInput.files || fileInput.files.length === 0) {
            event.preventDefault(); 
            alert('Please select a file to upload.'); 
        }
        });
        
    });
@endpush


