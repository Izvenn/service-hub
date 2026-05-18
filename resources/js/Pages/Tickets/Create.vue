<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array
});

const form = useForm({
    project_id: '',
    title: '',
    technical_info: '',
    attachment: null,
});

const submit = () => {
    form.post(route('tickets.store'), {
        onSuccess: () => form.reset(),
        forceFormData: true, // Garante o envio do arquivo
    });
};

const fileInput = ref(null); // Referência para o input de arquivo

const clearAttachment = () => {
    form.attachment = null;
    if (fileInput.value) {
        fileInput.value.value = ''; // Limpa o nome do arquivo no campo visual
    }
};

</script>

<template>

    <Head title="Novo Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Criar Novo Ticket</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <form @submit.prevent="submit" class="max-w-md space-y-6">
                        <!-- Projeto -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Projeto</label>
                            <select v-model="form.project_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500">
                                <option value="" disabled>Selecione um projeto</option>
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.project_id" class="text-red-500 text-xs mt-1">{{
                                form.errors.project_id }}
                            </div>
                        </div>

                        <!-- Título -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Título do Ticket</label>
                            <input type="text" v-model="form.title"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500"
                                placeholder="Ex: Erro ao carregar relatório" />
                            <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}
                            </div>
                        </div>
                        <!-- Descrição Técnica -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Descrição Técnica</label>
                            <textarea v-model="form.technical_info" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500"></textarea>
                            <div v-if="form.errors.technical_info" class="text-red-500 text-xs mt-1">
                                {{ form.errors.technical_info }}
                            </div>
                            <p class="mt-1 text-xs text-gray-500 italic">
                                Esta informação será anexada aos detalhes técnicos do ticket.
                            </p>
                        </div>

                        <!-- Anexo -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Anexo (JSON ou TXT)</label>

                            <div class="flex items-center gap-2 mt-1">
                                <input type="file" ref="fileInput" @input="form.attachment = $event.target.files[0]"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />

                                <!-- Botão de Remover -->
                                <button v-if="form.attachment" type="button" @click="clearAttachment"
                                    class="text-gray-500 hover:text-gray-700 text-sm font-medium px-2 py-1"
                                    title="Remover anexo">
                                    ✕
                                </button>
                            </div>

                            <progress v-if="form.progress" :value="form.progress.percentage" max="100"
                                class="w-full mt-2">
                                {{ form.progress.percentage }}%
                            </progress>

                            <div v-if="form.errors.attachment" class="text-red-500 text-xs mt-1">
                                {{ form.errors.attachment }}
                            </div>
                        </div>

                        <!-- Botão Submit -->
                        <div class="flex items-center gap-4">
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150">
                                {{ form.processing ? 'Criando...' : 'Criar Ticket' }}
                            </button>

                            <Transition enter-from-class="opacity-0" leave-to-class="opacity-0"
                                class="transition ease-in-out">
                                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Ticket criado com
                                    sucesso!</p>
                            </Transition>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>