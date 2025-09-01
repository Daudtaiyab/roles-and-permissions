@if ($errors->any())
    <div class="">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-red-700 font-medium">
                    <div role="alert">
                        <div class="border border-red-400 rounded bg-red-100 px-4 py-3 text-red-700">
                            <p>{{ $error }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
