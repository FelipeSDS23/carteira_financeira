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
                <h3>Você está realizando uma transferência de R${{$amount}} para {{$destination_account_user_name}} - {{$destination_account_user_cpf}}</h3>

                <div class="container mt-3">
                    <form action="{{ route('transaction.confirm') }}">
                        @csrf
                        <input type="hidden" name="" value="{{$id}}">
                        <button class="btn btn-success m-3">Confirmar</button>
                    </form>

                    <form action="{{ route('transaction.confirm') }}">
                        @csrf
                        <input type="hidden" name="" value="{{$id}}">
                        <button class="btn btn-danger m-3">Cancelar</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>