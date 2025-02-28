<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Account Statement') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="container px-4">
            <div class="dark:bg-dark shadow-sm rounded text-warning">

                <div class="card shadow-lg p-4 bg-dark">

                    <!-- Tabela de Transferências Feitas -->
                    <h3 class="mt-4 text-white">Transferências Feitas</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Para</th>
                                    <th>CPF</th>
                                    <th>Valor</th>
                                    <th>Data e Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfersMade as $transfer)
                                    <tr>
                                        <td>{{ $transfer->id }}</td>
                                        <td>{{ $transfer->destination_account_user_name }}</td>
                                        <td>{{ $transfer->destination_account_user_cpf }}</td>
                                        <td>R$ {{ number_format($transfer->amount, 2, ',', '.') }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($transfer->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <!-- Tabela de Transferências Recebidas -->
                    <h3 class="mt-4 text-white">Transferências Recebidas</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Para</th>
                                    <th>CPF</th>
                                    <th>Valor</th>
                                    <th>Data e Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfersReceived as $transfer)
                                    <tr>
                                        <td>{{ $transfer->id }}</td>
                                        <td>{{ $transfer->origin_account_user_name }}</td>
                                        <td>{{ $transfer->origin_account_user_cpf }}</td>
                                        <td>R$ {{ number_format($transfer->amount, 2, ',', '.') }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($transfer->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>



            </div>

        </div>
    </div>
    </div>
</x-app-layout>
