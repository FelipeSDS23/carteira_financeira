<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transfer') }}
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
                        <div class="text-warning ml-3">
                            Limite atual: R$ {{ $account->credit_limit }}
                        </div>
                    </div>

                    <div class="container">
                        <div class="card p-4 bg-dark">
                            <form action="{{ route('transaction.transfer') }}" method="POST" class="bg-dark text-white">
                                @csrf

                                <div class="mb-3">
                                    <label for="transferType" class="form-label">Transferir por:</label>
                                    <select class="form-select" id="transferType" name="identification" required>
                                        <option value="cpf">CPF</option>
                                        <option value="email">E-mail</option>
                                    </select>
                                    @error('identification')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3" x-data>
                                    <label for="destinationAccount" class="form-label">Destinatário:</label>
                                    <input type="text" class="form-control" id="destinationAccount" name="userIdentifier"
                                        placeholder="Informe o CPF do destinatário" required x-mask="999.999.999-99">
                                    @error('userIdentifier')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3" x-data="{
                                    valor: '',
                                    formatValor() {
                                        this.valor = parseFloat(this.valor.replace(/[^\d]/g, '') / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }).replace('R$', '').trim();
                                    }
                                }">
                                    <label for="valor" class="form-label">Valor a Transferir:</label>
                                    <input type="text" class="form-control" id="valor" x-model="valor" name="amount"
                                        x-on:input="formatValor" placeholder="Informe o valor a ser transferido"
                                        required>
                                    @error('amount')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Transferir</button>

                            </form>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const transferTypeSelect = document.getElementById('transferType');
    const destinationAccount = document.getElementById('destinationAccount');

    transferTypeSelect.addEventListener('change', function() {

        const selectedValue = transferTypeSelect.value;

        if (selectedValue == "email") { // Formata o input para receber e-mail
            destinationAccount.value = '';
            destinationAccount.removeAttribute('x-mask');
            destinationAccount.setAttribute('type', 'email');
            destinationAccount.setAttribute('placeholder', 'Informe o E-mail do destinatário');
        } else if (selectedValue == "cpf") { // Formata o input para receber cpf
            destinationAccount.value = '';
            destinationAccount.setAttribute('x-mask', '999.999.999-99');
            destinationAccount.setAttribute('type', 'text');
            destinationAccount.setAttribute('placeholder', 'Informe o CPF do destinatário');
        }
    });
</script>
