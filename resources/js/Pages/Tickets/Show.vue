<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object
});

const formatJson = (json) => {
    try {
        return typeof json === 'object' ? JSON.stringify(json, null, 2) : json;
    } catch (e) {
        return json;
    }
};
</script>

<template>
    <Head :title="`Ticket #${ticket.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ticket #{{ ticket.id }} - {{ ticket.title }}
                </h2>
                <span :class="{
                    'px-3 py-1 rounded-full text-xs font-bold uppercase': true,
                    'bg-yellow-100 text-yellow-800': ticket.status === 'open' || ticket.status === 'processing',
                    'bg-green-100 text-green-800': ticket.status === 'closed'
                }">
                    {{ ticket.status }}
                </span>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Informações Básicas -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Gerais</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Projeto</dt>
                            <dd class="text-base text-gray-900">{{ ticket.project.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Criado em</dt>
                            <dd class="text-base text-gray-900">{{ new Date(ticket.created_at).toLocaleString() }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Detalhamento Técnico -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhamento Técnico</h3>
                    
                    <!-- SEMPRE mostra a Descrição/Log Técnica se existir detalhe -->
                    <div v-if="ticket.detail" class="mb-6">
                        <span class="text-sm font-semibold text-gray-500">Descrição / Log de Processamento:</span>
                        <div class="mt-1 p-4 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-700 whitespace-pre-line">
                            {{ ticket.detail.technical_info }}
                        </div>
                    </div>

                    <!-- Área do Payload (Só se houver dados processados) -->
                    <div v-if="ticket.detail && ticket.detail.payload && Object.keys(ticket.detail.payload).length > 0">
                        <span class="text-sm font-semibold text-gray-500">Dados Extraídos do Anexo:</span>
                        <pre class="mt-2 p-4 bg-gray-900 text-green-400 rounded-lg overflow-x-auto font-mono text-xs shadow-inner">{{ formatJson(ticket.detail.payload) }}</pre>
                    </div>

                    <!-- Se tem anexo mas o Job ainda não terminou -->
                    <div v-else-if="ticket.attachment_path" class="flex items-center text-amber-600 bg-amber-50 p-4 rounded-lg border border-amber-100 mt-4">
                        <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Aguardando leitura do arquivo anexo...
                    </div>

                    <!-- Mensagem Final se não houver anexo (Mas o technical_info já apareceu acima) -->
                    <div v-else class="text-sm text-gray-400 italic py-2">
                        * Nenhum anexo enviado para processamento automático.
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>