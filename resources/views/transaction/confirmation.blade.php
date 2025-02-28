<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transfer') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="container px-4">
            <div class="dark:bg-dark shadow-sm rounded text-warning">
                <h1 class="text-white">Confirmação de transação</h1>
                <h3>Você está realizando uma transferência de R${{$amount}} para {{$destinatario}} - {{$cpf}}</h3>

                <div class="container mt-3">
                    <button class="btn btn-success m-3">Confirmar</button>
                    <button class="btn btn-danger m-3">Cancelar</button>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>