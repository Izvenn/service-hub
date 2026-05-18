<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    myCreatedTickets: Object,
    projectTickets: Object
});
</script>

<template>
    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ticket Dashboard</h2>
                <Link :href="route('tickets.create')" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    + New Ticket
                </Link>
            </div>
        </template>

        <div class="py-12 space-y-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- SEÇÃO 1: MEUS TICKETS -->
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-indigo-500 pl-2">My Requests</h3>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="ticket in myCreatedTickets.data" :key="ticket.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ ticket.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ticket.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ticket.project?.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            ticket.status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'
                                        ]">
                                            {{ ticket.status === 'open' ? 'Open' : 'Closed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('tickets.show', ticket.id)" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                            Details
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="myCreatedTickets.data.length === 0">
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">Você ainda não criou nenhum ticket.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SEÇÃO 2: TICKETS DO PROJETO -->
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-green-500 pl-2">Department Tickets</h3>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="ticket in projectTickets?.data" :key="ticket.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ ticket.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ticket.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ticket.project?.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            ticket.status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'
                                        ]">
                                            {{ ticket.status === 'open' ? 'Open' : 'Closed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('tickets.show', ticket.id)" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                            Details
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="!projectTickets || projectTickets.data.length === 0">
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">No project tickets found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>