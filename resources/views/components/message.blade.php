@if (session('success'))
    <div role="alert">
        <div class="border border-green-400-400 rounded bg-green-100 px-4 py-3 text-green-700">
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif

@if (session('error'))
    <div role="alert">
        <div class="border border-red-400 rounded bg-red-100 px-4 py-3 text-red-700">
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif
