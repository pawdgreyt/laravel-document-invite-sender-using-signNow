<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Send Contract
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Your import form here -->
            <form action="{{ route('import.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file" accept=".csv">
                <button type="submit" class="btn btn-outline-primary">Import</button>
            </form>
        </div>
    </div>
</x-app-layout>
