@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="card-header">
                            <h3>Select company and make a event!</h3>
                            <div class="card-body">
                                <div class="card-body">
                                    <!-- Formularz do wyboru typu -->
                                    <form id="typeForm">
                                        @csrf <!-- Dodaj to @csrf -->
                                        <label for="type_id">Select Type:</label>
                                        <select name="type_id" id="type_id" required>
                                            <option value="">Select a type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>

                                    <!-- Formularz do wyboru firmy i utworzenia wydarzenia -->
                                    <form id="eventForm" style="display: none;">
                                        @csrf <!-- Dodaj to @csrf -->
                                        <label for="event_name">Event Name:</label>
                                        <input type="text" name="event_name" required>

                                        <label for="event_start">Event Date:</label>
                                        <input type="date" name="event_start" required>

                                        <!-- Wybór firmy -->
                                        <label for="company_id">Select Company:</label>
                                        <select name="company_id" id="company_id" required>
                                            <option value="">Select a company</option>
                                        </select>

                                        <button type="submit">Create Event</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Skrypt do obsługi wyboru typu i dynamicznego wczytywania firm
        document.addEventListener('DOMContentLoaded', function () {
            const typeForm = document.getElementById('typeForm');
            const eventForm = document.getElementById('eventForm');
            const companySelect = document.getElementById('company_id');

            typeForm.addEventListener('change', function () {
                // Pobierz wybrany type_id
                const typeId = this.elements['type_id'].value;

                if (typeId) {
                    // Wysyłamy zapytanie AJAX do pobrania firm o wybranym typie
                    fetch("{{ route('events.getCompaniesByType') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            type_id: typeId
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Wypełnij select z firmami danymi pobranymi z serwera
                            companySelect.innerHTML = '<option value="">Select a company</option>';
                            data.forEach(company => {
                                companySelect.innerHTML += `<option value="${company.id}">${company.companyName}</option>`;
                            });

                            // Pokaż formularz do wyboru firmy
                            eventForm.style.display = 'block';
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    // Wybrano "Select a type", ukryj formularz do wyboru firmy
                    eventForm.style.display = 'none';
                }
            });

            eventForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Zapobiegnij domyślnej akcji formularza (przekierowanie)

                // Pobierz dane z formularza
                const formData = new FormData(eventForm);

                // Wysyłamy zapytanie AJAX do zapisania wydarzenia
                fetch("{{ route('events.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        // Wyświetl komunikat o sukcesie
                        alert(data.message);

                        // Wyczyść formularz
                        eventForm.reset();
                    })
                    .catch(error => {
                        // Wyświetl komunikat o błędzie
                        alert('Error while creating event.');
                        console.error(error);
                    });
            });
        });
    </script>
@endsection
