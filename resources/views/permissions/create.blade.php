<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">
                    <x-allerrors></x-allerrors>
                    <form action="{{ route('permissions.store') }}" method="post">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <div class="mb-3">
                                <label for="">Permission</label>
                                <input type="text" value="{{ old('name') }}" name="name"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                            {{-- <div class="mb-3">
                                <label for="">Permission</label>
                                <input type="text" name="permission"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div> --}}
                        </div>
                        <button class="bg-slate-900 text-sm rounded-md text-white px-4 py-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
