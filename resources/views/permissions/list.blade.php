<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permissions List') }}
            </h2>
            <a class="bg-slate-600 text-white text-center text-sm py-2 px-4 rounded"
                href="{{ route('permissions.create') }}">Create</a>
        </div>
    </x-slot>
    {{-- @php
        dd($permissions);
    @endphp --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="errors">
                        <x-message></x-message>
                    </div>
                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <td class="text-center">Id</td>
                                <td class="text-center">Permission</td>
                                <td class="text-center">Created At</td>
                                <td class="text-center">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr class="text-center">
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permission->created_at)->format('d M, Y') }}</td>
                                    <td class="">
                                        @can('permissions.edit')
                                            <a class="bg-slate-600 text-white text-center text-sm py-2 px-4 rounded"
                                                href="{{ route('permissions.edit', $permission->id) }}">Edit</a>
                                        @endcan
                                        @can('permissions.delete')
                                            <a class="bg-red-600 text-white text-center text-sm py-2 px-4 rounded deletePermission"
                                                data-id="{{ $permission->id }}">Delete</a>
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            $(document).on('click', '.deletePermission', function() {
                var thisClicked = $(this);
                var id = thisClicked.data('id');
                // alert(id);
                if (confirm('Are you want to delete?')) {
                    $.ajax({
                        type: "post",
                        url: '{{ route('permissions.delete') }}',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == true) {
                                $('.errors').html(`
    <div role="alert">
        <div class="border border-green-400-400 rounded bg-green-100 px-4 py-3 text-green-700">
            <p>${response.message}</p>
        </div>
    </div>`);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);

                            } else {
                                $('.errors').html(`
    <div role="alert">
        <div class="border border-red-400-400 rounded bg-red-100 px-4 py-3 text-red-700">
            <p>${response.message}</p>
        </div>
    </div>`);
                            }
                        }
                    });
                }
            })
        </script>
    </x-slot>
</x-app-layout>
@push('script')
    <script></script>
@endpush
