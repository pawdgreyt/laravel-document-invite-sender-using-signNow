<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            CSV Preview
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- CSV preview table -->
            <form action="{{ route('import.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="csv_filename" value="{{ $filename }}">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><center>Select All <input type="checkbox" id="select-all"></center></th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>COMPANY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($csvData as $index => $row)
                            @if ($index != 0)
                                <tr>
                                    <td><center><input type="checkbox" class="row-checkbox" name="selected_rows[]" value="{{ $row[1] }}"></center></td>
                                    @foreach ($row as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-outline-dark">Send Invites to Selected Rows</button>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');

        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // Check if all individual checkboxes are checked
                    const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
    });
</script>