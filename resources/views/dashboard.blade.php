<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="container px-4">
            <div class="dark:bg-dark shadow-sm rounded text-warning">

                <div class="py-1 px-4 fs-4">
                    <p>Olá {{ explode(' ', Auth::user()->name)[0] }}!</p>
                </div>


                <div class="card shadow-lg p-4 bg-dark">
                    <div class="mb-3">
                        <h5 class="text-warning">Saldo Disponível:</h5>
                        <p class="fw-bold text-success fs-4">R$ 2.500,00</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="text-warning">Limite de Crédito:</h5>
                        <p class="fw-bold text-primary fs-4">R$ 5.000,00</p>
                    </div>
                    <div class="d-flex gap-3">

                        <button class="btn btn-primary px-3 d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined">
                                sync_alt
                            </span>
                            <span class="ml-1">Transferir</span>
                        </button>

                        <button class="btn btn-success px-3 d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined">
                                savings
                            </span>
                            <span class="ml-1">Depositar</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
