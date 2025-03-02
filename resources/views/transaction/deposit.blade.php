<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Deposit') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="container px-4">
            <div class="dark:bg-dark shadow-sm rounded text-warning">

              <div class="card shadow-lg p-0 bg-dark">

                <div class="d-flex mb-3 p-2">
                    <div class="text-warning">
                        Saldo atual: R$ {{ $account->balance }}
                    </div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="container">
                    <div class="card p-4 bg-dark">
                        <form action="{{ route('transaction.deposit') }}" method="POST" class="bg-dark text-white">
                            @csrf

                            <div class="mb-3" x-data="{
                                valor: '',
                                formatValor() {
                                    this.valor = parseFloat(this.valor.replace(/[^\d]/g, '') / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }).replace('R$', '').trim();
                                }
                            }">
                                <label for="valor" class="form-label">Valor do depósito:</label>
                                <input type="text" class="form-control" id="valor" x-model="valor" name="amount"
                                    x-on:input="formatValor" placeholder="Informe o valor a ser depositado"
                                    required>
                                @error('amount')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Depositar</button>

                        </form>
                    </div>
                </div>


            </div>

            </div>
        </div>
    </div>
</x-app-layout>
